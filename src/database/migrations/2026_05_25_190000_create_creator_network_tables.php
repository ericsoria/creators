<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prospects', function (Blueprint $table) {
            $table->id();
            $table->string('prospect_type')->default('creator')->index();
            $table->string('platform')->index();
            $table->string('handle')->index();
            $table->string('profile_url')->nullable();
            $table->string('name')->nullable();
            $table->string('city_name')->nullable();
            $table->string('country_name')->nullable();
            $table->string('category')->nullable()->index();
            $table->string('status')->default('discovered')->index();
            $table->timestamp('contacted_at')->nullable()->index();
            $table->timestamp('responded_at')->nullable()->index();
            $table->timestamp('approved_at')->nullable()->index();
            $table->string('rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->string('source')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('creators', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->nullable()->unique();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->text('bio')->nullable();
            $table->boolean('ugc_only')->default(false)->index();
            $table->boolean('accepts_barter')->default(true)->index();
            $table->string('status')->default('active')->index();
            $table->unsignedTinyInteger('rating')->nullable();
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('last_active_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('social_accounts', function (Blueprint $table) {
            $table->id();
            $table->morphs('accountable');
            $table->string('platform')->index();
            $table->string('handle')->index();
            $table->string('url')->nullable();
            $table->boolean('is_primary')->default(false)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['accountable_type', 'accountable_id', 'platform', 'handle'], 'social_account_owner_platform_handle_unique');
        });

        Schema::create('creator_city', function (Blueprint $table) {
            $table->foreignId('creator_id')->constrained()->cascadeOnDelete();
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();
            $table->primary(['creator_id', 'city_id']);
        });

        Schema::create('creator_tag', function (Blueprint $table) {
            $table->foreignId('creator_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->primary(['creator_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creator_tag');
        Schema::dropIfExists('creator_city');
        Schema::dropIfExists('social_accounts');
        Schema::dropIfExists('creators');
        Schema::dropIfExists('prospects');
    }
};
