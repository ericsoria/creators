<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('creator_leads') && ! Schema::hasTable('prospects')) {
            Schema::rename('creator_leads', 'prospects');
        }

        if (! Schema::hasTable('prospects')) {
            return;
        }

        Schema::table('prospects', function (Blueprint $table): void {
            if (! Schema::hasColumn('prospects', 'prospect_type')) {
                $table->string('prospect_type')->default('creator')->index()->after('id');
            }

            if (Schema::hasColumn('prospects', 'niche') && ! Schema::hasColumn('prospects', 'category')) {
                $table->renameColumn('niche', 'category');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('prospects')) {
            return;
        }

        Schema::table('prospects', function (Blueprint $table): void {
            if (Schema::hasColumn('prospects', 'category') && ! Schema::hasColumn('prospects', 'niche')) {
                $table->renameColumn('category', 'niche');
            }
        });

        if (! Schema::hasTable('creator_leads')) {
            Schema::rename('prospects', 'creator_leads');
        }
    }
};
