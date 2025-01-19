<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        User::factory()->create([
            'phone_number' => '0911111111',
            'first_name'=>'laith',
            'last_name'=>'jawish',
            'location'=>'Damascus',
            'password' => 'admin1234',
            'role'=>'admin'
        ]);
        Store::factory()
            ->hasProducts(30) // Each store will have 5 products
            ->count(30) // Create 10 stores
            ->create();

        
    }


       
    
}
