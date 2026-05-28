<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->restrictOnDelete();
            $table->foreignId('creator_id')->constrained()->restrictOnDelete();
            $table->string('status')->default('draft')->index();
            $table->string('channel')->nullable()->index();
            $table->string('source_account')->nullable();
            $table->text('message_template')->nullable();
            $table->timestamp('first_contacted_at')->nullable()->index();
            $table->timestamp('last_contacted_at')->nullable()->index();
            $table->timestamp('responded_at')->nullable()->index();
            $table->unsignedSmallInteger('follow_up_count')->default(0);
            $table->string('rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('converted_to_collaboration_id')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['campaign_id', 'creator_id']);
            $table->index(['assigned_to', 'status']);
        });

        Schema::create('opportunity_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opportunity_id')->constrained()->cascadeOnDelete();
            $table->string('type')->index();
            $table->string('old_status')->nullable()->index();
            $table->string('new_status')->nullable()->index();
            $table->text('message')->nullable();
            $table->json('metadata')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['opportunity_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opportunity_events');
        Schema::dropIfExists('opportunities');
    }
};
