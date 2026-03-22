<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Catalog\Database\Seeders\CatalogDatabaseSeeder;
use Modules\Order\Database\Seeders\OrderDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => 'password',
        ]);

        $this->call([
            CatalogDatabaseSeeder::class,
            OrderDatabaseSeeder::class,
        ]);
    }
}
