<?php

namespace App\Actions;

use App\Models\Brand;
use App\Models\Prospect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ApproveBrandProspectAction
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function execute(Prospect $prospect, array $attributes = []): Brand
    {
        if ($prospect->prospect_type !== Prospect::TYPE_BRAND) {
            throw ValidationException::withMessages([
                'prospect' => ['Prospect is not a brand prospect.'],
            ]);
        }

        if ($prospect->status === Prospect::STATUS_APPROVED) {
            throw ValidationException::withMessages([
                'prospect' => ['Prospect is already approved.'],
            ]);
        }

        return DB::transaction(function () use ($prospect, $attributes): Brand {
            $name = $attributes['name'] ?? $prospect->name ?? $prospect->handle;

            $brand = Brand::query()->create([
                'name' => $name,
                'slug' => $attributes['slug'] ?? $this->uniqueSlug($name),
                'industry' => $attributes['industry'] ?? $prospect->category,
                'description' => $attributes['description'] ?? null,
                'website_url' => $attributes['website_url'] ?? null,
                'status' => $attributes['status'] ?? 'active',
                'notes' => $attributes['notes'] ?? $prospect->notes,
            ]);

            if ($prospect->platform && $prospect->handle) {
                $brand->socialAccounts()->create([
                    'platform' => $prospect->platform,
                    'handle' => $prospect->handle,
                    'url' => $prospect->profile_url,
                    'is_primary' => true,
                ]);
            }

            $prospect->update([
                'status' => Prospect::STATUS_APPROVED,
                'approved_at' => now(),
            ]);

            return $brand->load(['cities', 'tags', 'socialAccounts']);
        });
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'brand';
        $slug = $base;
        $counter = 2;

        while (Brand::query()->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
