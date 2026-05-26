<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListCampaignsRequest;
use App\Http\Requests\StoreCampaignRequest;
use App\Http\Requests\UpdateCampaignRequest;
use App\Http\Resources\CampaignResource;
use App\Models\Campaign;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CampaignController extends Controller
{
    public function index(ListCampaignsRequest $request): AnonymousResourceCollection
    {
        $filters = $request->validated();

        return CampaignResource::collection(
            Campaign::query()
                ->with(['brand', 'tags'])
                ->filter($filters)
                ->latest('id')
                ->paginate($filters['per_page'] ?? 15),
        );
    }

    public function store(StoreCampaignRequest $request): JsonResponse
    {
        $data = $request->validated();
        $tagIds = $data['tag_ids'] ?? [];
        unset($data['tag_ids']);

        $campaign = Campaign::query()->create($data);
        $campaign->tags()->sync($tagIds);

        return (new CampaignResource($campaign->load(['brand', 'tags'])))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Campaign $campaign): CampaignResource
    {
        return new CampaignResource($campaign->load(['brand', 'tags']));
    }

    public function update(UpdateCampaignRequest $request, Campaign $campaign): CampaignResource
    {
        $data = $request->validated();
        $hasTagIds = array_key_exists('tag_ids', $data);
        $tagIds = $data['tag_ids'] ?? [];
        unset($data['tag_ids']);

        $campaign->update($data);

        if ($hasTagIds) {
            $campaign->tags()->sync($tagIds);
        }

        return new CampaignResource($campaign->load(['brand', 'tags']));
    }

    public function destroy(Campaign $campaign): Response
    {
        $campaign->delete();

        return response()->noContent();
    }
}
