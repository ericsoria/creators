<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListSocialAccountsRequest;
use App\Http\Requests\StoreSocialAccountRequest;
use App\Http\Requests\UpdateSocialAccountRequest;
use App\Http\Resources\SocialAccountResource;
use App\Models\Brand;
use App\Models\Creator;
use App\Models\SocialAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class SocialAccountController extends Controller
{
    public function index(ListSocialAccountsRequest $request): AnonymousResourceCollection
    {
        $filters = $request->validated();

        return SocialAccountResource::collection(
            SocialAccount::query()->filter($filters)->latest('id')->paginate($filters['per_page'] ?? 15),
        );
    }

    public function store(StoreSocialAccountRequest $request): JsonResponse
    {
        $data = $request->validated();
        $owner = $this->resolveOwner($data['accountable_type'], $data['accountable_id']);
        unset($data['accountable_type'], $data['accountable_id']);

        if ($data['is_primary'] ?? false) {
            $this->clearPrimary($owner, $data['platform']);
        }

        return (new SocialAccountResource($owner->socialAccounts()->create($data)))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(SocialAccount $socialAccount): SocialAccountResource
    {
        return new SocialAccountResource($socialAccount);
    }

    public function update(UpdateSocialAccountRequest $request, SocialAccount $socialAccount): SocialAccountResource
    {
        $data = $request->validated();

        if ($data['is_primary'] ?? false) {
            $this->clearPrimary($socialAccount->accountable, $data['platform'] ?? $socialAccount->platform);
        }

        $socialAccount->update($data);

        return new SocialAccountResource($socialAccount);
    }

    public function destroy(SocialAccount $socialAccount): Response
    {
        $socialAccount->delete();

        return response()->noContent();
    }

    public function creatorIndex(Creator $creator): AnonymousResourceCollection
    {
        return SocialAccountResource::collection($creator->socialAccounts()->latest('id')->get());
    }

    public function brandIndex(Brand $brand): AnonymousResourceCollection
    {
        return SocialAccountResource::collection($brand->socialAccounts()->latest('id')->get());
    }

    private function resolveOwner(string $type, int $id): Model
    {
        $owner = $type::query()->find($id);

        if (! $owner instanceof Creator && ! $owner instanceof Brand) {
            throw ValidationException::withMessages(['accountable_id' => ['The selected owner is invalid.']]);
        }

        return $owner;
    }

    private function clearPrimary(Model $owner, string $platform): void
    {
        $owner->socialAccounts()->where('platform', $platform)->update(['is_primary' => false]);
    }
}
