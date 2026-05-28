<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListOpportunityEventsRequest;
use App\Http\Requests\StoreOpportunityEventRequest;
use App\Http\Resources\OpportunityEventResource;
use App\Models\Opportunity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class OpportunityEventController extends Controller
{
    public function index(ListOpportunityEventsRequest $request, Opportunity $opportunity): AnonymousResourceCollection
    {
        $filters = $request->validated();

        return OpportunityEventResource::collection(
            $opportunity->events()
                ->with('createdBy')
                ->oldest('created_at')
                ->paginate($filters['per_page'] ?? 50),
        );
    }

    public function store(StoreOpportunityEventRequest $request, Opportunity $opportunity): JsonResponse
    {
        $event = DB::transaction(function () use ($request, $opportunity) {
            $data = $request->validated();
            $oldStatus = $data['old_status'] ?? $opportunity->status;

            if (isset($data['new_status']) && $data['new_status'] !== $opportunity->status) {
                $opportunity->update(['status' => $data['new_status']]);
            }

            return $opportunity->events()->create([
                ...$data,
                'old_status' => $oldStatus,
                'created_by' => $request->user()->id,
            ]);
        });

        return (new OpportunityEventResource($event->load('createdBy')))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
