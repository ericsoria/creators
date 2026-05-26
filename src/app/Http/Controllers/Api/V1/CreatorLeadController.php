<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\ApproveCreatorLeadAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApproveCreatorLeadRequest;
use App\Http\Requests\ListCreatorLeadsRequest;
use App\Http\Requests\StoreCreatorLeadRequest;
use App\Http\Requests\UpdateCreatorLeadRequest;
use App\Http\Resources\CreatorLeadResource;
use App\Http\Resources\CreatorResource;
use App\Models\CreatorLead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CreatorLeadController extends Controller
{
    public function index(ListCreatorLeadsRequest $request): AnonymousResourceCollection
    {
        $filters = $request->validated();

        return CreatorLeadResource::collection(
            CreatorLead::query()->filter($filters)->latest('id')->paginate($filters['per_page'] ?? 15),
        );
    }

    public function store(StoreCreatorLeadRequest $request): JsonResponse
    {
        return (new CreatorLeadResource(CreatorLead::query()->create($request->validated())))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CreatorLead $creatorLead): CreatorLeadResource
    {
        return new CreatorLeadResource($creatorLead);
    }

    public function update(UpdateCreatorLeadRequest $request, CreatorLead $creatorLead): CreatorLeadResource
    {
        $creatorLead->update($request->validated());

        return new CreatorLeadResource($creatorLead);
    }

    public function destroy(CreatorLead $creatorLead): Response
    {
        $creatorLead->delete();

        return response()->noContent();
    }

    public function approve(ApproveCreatorLeadRequest $request, CreatorLead $creatorLead, ApproveCreatorLeadAction $action): JsonResponse
    {
        return (new CreatorResource($action->execute($creatorLead, $request->validated())))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
