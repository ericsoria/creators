<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListCreatorsRequest;
use App\Http\Requests\StoreCreatorRequest;
use App\Http\Requests\UpdateCreatorRequest;
use App\Http\Resources\CreatorResource;
use App\Models\Creator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CreatorController extends Controller
{
    public function index(ListCreatorsRequest $request): AnonymousResourceCollection
    {
        $filters = $request->validated();

        return CreatorResource::collection(
            Creator::query()->with(['cities', 'tags', 'socialAccounts'])->filter($filters)->latest('id')->paginate($filters['per_page'] ?? 15),
        );
    }

    public function store(StoreCreatorRequest $request): JsonResponse
    {
        $data = $request->validated();
        $cityIds = $data['city_ids'] ?? [];
        $tagIds = $data['tag_ids'] ?? [];
        unset($data['city_ids'], $data['tag_ids']);

        $creator = Creator::query()->create($data);
        $creator->cities()->sync($cityIds);
        $creator->tags()->sync($tagIds);

        return (new CreatorResource($creator->load(['cities', 'tags', 'socialAccounts'])))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Creator $creator): CreatorResource
    {
        return new CreatorResource($creator->load(['cities', 'tags', 'socialAccounts']));
    }

    public function update(UpdateCreatorRequest $request, Creator $creator): CreatorResource
    {
        $data = $request->validated();
        $hasCityIds = array_key_exists('city_ids', $data);
        $hasTagIds = array_key_exists('tag_ids', $data);
        $cityIds = $data['city_ids'] ?? [];
        $tagIds = $data['tag_ids'] ?? [];
        unset($data['city_ids'], $data['tag_ids']);

        $creator->update($data);

        if ($hasCityIds) {
            $creator->cities()->sync($cityIds);
        }

        if ($hasTagIds) {
            $creator->tags()->sync($tagIds);
        }

        return new CreatorResource($creator->load(['cities', 'tags', 'socialAccounts']));
    }

    public function destroy(Creator $creator): Response
    {
        $creator->delete();

        return response()->noContent();
    }
}
