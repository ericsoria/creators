<?php

namespace Tests\Feature\Api;

use App\Models\Campaign;
use App\Models\Creator;
use App\Models\Opportunity;
use App\Models\OpportunityEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OpportunityPipelineTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_update_show_list_and_delete_opportunity(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $campaign = Campaign::factory()->create();
        $creator = Creator::factory()->create();
        $assigned = User::factory()->create();

        $response = $this->postJson('/api/v1/opportunities', [
            'campaign_id' => $campaign->id,
            'creator_id' => $creator->id,
            'status' => Opportunity::STATUS_CONTACTED,
            'channel' => 'instagram_dm',
            'source_account' => '@brand',
            'message_template' => 'Want to visit?',
            'first_contacted_at' => '2026-05-01T10:00:00Z',
            'last_contacted_at' => '2026-05-02T10:00:00Z',
            'follow_up_count' => 1,
            'assigned_to' => $assigned->id,
            'notes' => 'Priority creator.',
        ])->assertCreated();

        $opportunityId = $response->json('data.id');

        $this->patchJson("/api/v1/opportunities/{$opportunityId}", [
            'status' => Opportunity::STATUS_INTERESTED,
            'responded_at' => '2026-05-03T10:00:00Z',
            'notes' => 'Creator replied positively.',
        ])
            ->assertOk()
            ->assertJsonPath('data.status', Opportunity::STATUS_INTERESTED)
            ->assertJsonPath('data.creator.id', $creator->id)
            ->assertJsonPath('data.campaign.id', $campaign->id)
            ->assertJsonPath('data.assigned_user.id', $assigned->id);

        $this->getJson("/api/v1/opportunities/{$opportunityId}")
            ->assertOk()
            ->assertJsonPath('data.id', $opportunityId)
            ->assertJsonStructure(['data' => ['id', 'campaign', 'creator', 'events']]);

        $this->getJson('/api/v1/opportunities')
            ->assertOk()
            ->assertJsonStructure(['data', 'meta', 'links']);

        $this->deleteJson("/api/v1/opportunities/{$opportunityId}")->assertNoContent();
        $this->assertSoftDeleted(Opportunity::class, ['id' => $opportunityId]);
    }

    public function test_opportunity_filters_match_expected_records(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $campaign = Campaign::factory()->create();
        $creator = Creator::factory()->create();
        $assigned = User::factory()->create();
        $matching = Opportunity::factory()->for($campaign)->for($creator)->create([
            'status' => Opportunity::STATUS_CONTACTED,
            'channel' => 'email',
            'assigned_to' => $assigned->id,
            'responded_at' => '2026-05-03 00:00:00',
            'first_contacted_at' => '2026-05-01 00:00:00',
            'last_contacted_at' => '2026-05-02 00:00:00',
        ]);
        $other = Opportunity::factory()->create([
            'status' => Opportunity::STATUS_DRAFT,
            'channel' => 'instagram_dm',
            'responded_at' => null,
        ]);

        $this->getJson("/api/v1/opportunities?campaign={$campaign->id}&creator={$creator->id}&status=contacted&channel=email&assigned_to={$assigned->id}&responded=1&first_contacted_at=2026-05-01&last_contacted_at=2026-05-02")
            ->assertOk()
            ->assertJsonPath('data.0.id', $matching->id)
            ->assertJsonPath('data.1', null)
            ->assertJsonPath('meta.total', 1);
    }

    public function test_duplicate_active_opportunities_are_rejected_but_terminal_outcomes_allow_reoutreach(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $campaign = Campaign::factory()->create();
        $creator = Creator::factory()->create();

        Opportunity::factory()->for($campaign)->for($creator)->active()->create();

        $this->postJson('/api/v1/opportunities', [
            'campaign_id' => $campaign->id,
            'creator_id' => $creator->id,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('creator_id');

        Opportunity::query()->update(['status' => Opportunity::STATUS_REJECTED]);

        $this->postJson('/api/v1/opportunities', [
            'campaign_id' => $campaign->id,
            'creator_id' => $creator->id,
        ])->assertCreated();
    }

    public function test_accepting_opportunity_records_transition_event_and_rejects_terminal_reacceptance(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $opportunity = Opportunity::factory()->active()->create(['responded_at' => null]);

        $this->postJson("/api/v1/opportunities/{$opportunity->id}/accept", [
            'message' => 'Creator accepted.',
            'metadata' => ['channel' => 'dm'],
        ])
            ->assertOk()
            ->assertJsonPath('data.status', Opportunity::STATUS_ACCEPTED)
            ->assertJsonPath('data.events.0.type', OpportunityEvent::TYPE_ACCEPTED)
            ->assertJsonPath('data.events.0.created_user.id', $user->id);

        $this->assertDatabaseHas('opportunity_events', [
            'opportunity_id' => $opportunity->id,
            'type' => OpportunityEvent::TYPE_ACCEPTED,
            'old_status' => Opportunity::STATUS_CONTACTED,
            'new_status' => Opportunity::STATUS_ACCEPTED,
            'created_by' => $user->id,
        ]);

        $this->postJson("/api/v1/opportunities/{$opportunity->id}/accept")
            ->assertUnprocessable()
            ->assertJsonValidationErrors('status');
    }

    public function test_opportunity_event_list_create_validation_and_status_update(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $opportunity = Opportunity::factory()->active()->create();
        OpportunityEvent::factory()->for($opportunity)->create(['created_at' => now()->subMinute()]);

        $this->postJson("/api/v1/opportunities/{$opportunity->id}/events", [
            'type' => OpportunityEvent::TYPE_CREATOR_REPLIED,
            'new_status' => Opportunity::STATUS_INTERESTED,
            'message' => 'Interested in details.',
            'metadata' => ['sentiment' => 'positive'],
        ])
            ->assertCreated()
            ->assertJsonPath('data.type', OpportunityEvent::TYPE_CREATOR_REPLIED)
            ->assertJsonPath('data.old_status', Opportunity::STATUS_CONTACTED)
            ->assertJsonPath('data.new_status', Opportunity::STATUS_INTERESTED)
            ->assertJsonPath('data.created_user.id', $user->id);

        $this->assertDatabaseHas('opportunities', [
            'id' => $opportunity->id,
            'status' => Opportunity::STATUS_INTERESTED,
        ]);

        $this->getJson("/api/v1/opportunities/{$opportunity->id}/events")
            ->assertOk()
            ->assertJsonStructure(['data', 'meta', 'links']);

        $this->postJson("/api/v1/opportunities/{$opportunity->id}/events", [
            'type' => 'unsupported',
            'new_status' => 'bad',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['type', 'new_status']);
    }

    public function test_campaign_creator_opportunity_event_relationships(): void
    {
        $opportunity = Opportunity::factory()->create();
        $event = OpportunityEvent::factory()->for($opportunity)->create();

        $this->assertTrue($opportunity->campaign->opportunities->contains($opportunity));
        $this->assertTrue($opportunity->creator->opportunities->contains($opportunity));
        $this->assertTrue($opportunity->events->contains($event));
        $this->assertTrue($event->opportunity->is($opportunity));
    }
}
