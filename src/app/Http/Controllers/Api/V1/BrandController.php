<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListBrandsRequest;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class BrandController extends Controller
{
    public function index(ListBrandsRequest $request): AnonymousResourceCollection
    {
        $filters = $request->validated();

        return BrandResource::collection(
            Brand::query()
                ->with(['cities', 'tags'])
                ->filter($filters)
                ->latest('id')
                ->paginate($filters['per_page'] ?? 15),
        );
    }

    public function store(StoreBrandRequest $request): JsonResponse
    {
        $data = $request->validated();
        $cityIds = $data['city_ids'] ?? [];
        $tagIds = $data['tag_ids'] ?? [];
        unset($data['city_ids'], $data['tag_ids']);

        $brand = Brand::query()->create($data);
        $brand->cities()->sync($cityIds);
        $brand->tags()->sync($tagIds);

        return (new BrandResource($brand->load(['cities', 'tags'])))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Brand $brand): BrandResource
    {
        return new BrandResource($brand->load(['cities', 'tags']));
    }

    public function update(UpdateBrandRequest $request, Brand $brand): BrandResource
    {
        $data = $request->validated();
        $hasCityIds = array_key_exists('city_ids', $data);
        $hasTagIds = array_key_exists('tag_ids', $data);
        $cityIds = $data['city_ids'] ?? [];
        $tagIds = $data['tag_ids'] ?? [];
        unset($data['city_ids'], $data['tag_ids']);

        $brand->update($data);

        if ($hasCityIds) {
            $brand->cities()->sync($cityIds);
        }

        if ($hasTagIds) {
            $brand->tags()->sync($tagIds);
        }

        return new BrandResource($brand->load(['cities', 'tags']));
    }

    public function destroy(Brand $brand): Response
    {
        $brand->delete();

        return response()->noContent();
    }
}
