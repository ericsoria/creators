<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\AcceptOpportunityAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\AcceptOpportunityRequest;
use App\Http\Requests\ListOpportunitiesRequest;
use App\Http\Requests\StoreOpportunityRequest;
use App\Http\Requests\UpdateOpportunityRequest;
use App\Http\Resources\OpportunityResource;
use App\Models\Opportunity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class OpportunityController extends Controller
{
    public function index(ListOpportunitiesRequest $request): AnonymousResourceCollection
    {
        $filters = $request->validated();

        return OpportunityResource::collection(
            Opportunity::query()
                ->with(['campaign.brand', 'creator', 'assignedUser'])
                ->filter($filters)
                ->latest('id')
                ->paginate($filters['per_page'] ?? 15),
        );
    }

    public function store(StoreOpportunityRequest $request): JsonResponse
    {
        $opportunity = Opportunity::query()->create($request->validated());

        return (new OpportunityResource($opportunity->load(['campaign.brand', 'creator', 'assignedUser'])))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Opportunity $opportunity): OpportunityResource
    {
        return new OpportunityResource($opportunity->load(['campaign.brand', 'creator', 'assignedUser', 'events.createdBy']));
    }

    public function update(UpdateOpportunityRequest $request, Opportunity $opportunity): OpportunityResource
    {
        $opportunity->update($request->validated());

        return new OpportunityResource($opportunity->load(['campaign.brand', 'creator', 'assignedUser']));
    }

    public function destroy(Opportunity $opportunity): Response
    {
        $opportunity->delete();

        return response()->noContent();
    }

    public function accept(AcceptOpportunityRequest $request, Opportunity $opportunity, AcceptOpportunityAction $action): OpportunityResource
    {
        $opportunity = $action->execute($opportunity, $request->user(), $request->validated());

        return new OpportunityResource($opportunity->load(['campaign.brand', 'creator', 'assignedUser', 'events.createdBy']));
    }
}
