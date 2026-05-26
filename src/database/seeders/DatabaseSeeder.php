<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(TaxonomySeeder::class);

        User::query()->firstOrCreate(
            ['email' => config('internal.admin_user.email')],
            [
                'name' => config('internal.admin_user.name'),
                'password' => config('internal.admin_user.password'),
                'role' => User::ROLE_ADMIN,
            ],
        );

        $this->call(DemoCatalogSeeder::class);
        $this->call(DemoCreatorNetworkSeeder::class);
    }
}
