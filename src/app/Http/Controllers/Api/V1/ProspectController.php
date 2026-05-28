<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\ApproveBrandProspectAction;
use App\Actions\ApproveCreatorProspectAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApproveBrandProspectRequest;
use App\Http\Requests\ApproveCreatorProspectRequest;
use App\Http\Requests\ListProspectsRequest;
use App\Http\Requests\StoreProspectRequest;
use App\Http\Requests\UpdateProspectRequest;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CreatorResource;
use App\Http\Resources\ProspectResource;
use App\Models\Prospect;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ProspectController extends Controller
{
    public function index(ListProspectsRequest $request): AnonymousResourceCollection
    {
        $filters = $request->validated();

        return ProspectResource::collection(
            Prospect::query()->filter($filters)->latest('id')->paginate($filters['per_page'] ?? 15),
        );
    }

    public function store(StoreProspectRequest $request): JsonResponse
    {
        return (new ProspectResource(Prospect::query()->create($request->validated())))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Prospect $prospect): ProspectResource
    {
        return new ProspectResource($prospect);
    }

    public function update(UpdateProspectRequest $request, Prospect $prospect): ProspectResource
    {
        $prospect->update($request->validated());

        return new ProspectResource($prospect);
    }

    public function destroy(Prospect $prospect): Response
    {
        $prospect->delete();

        return response()->noContent();
    }

    public function approveAsCreator(ApproveCreatorProspectRequest $request, Prospect $prospect, ApproveCreatorProspectAction $action): JsonResponse
    {
        return (new CreatorResource($action->execute($prospect, $request->validated())))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function approveAsBrand(ApproveBrandProspectRequest $request, Prospect $prospect, ApproveBrandProspectAction $action): JsonResponse
    {
        return (new BrandResource($action->execute($prospect, $request->validated())))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
