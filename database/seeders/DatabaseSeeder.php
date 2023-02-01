<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        Schema::disableForeignKeyConstraints();

        DB::table('orders')->truncate();
        DB::table('customers')->truncate();
        DB::table('users')->truncate();

        User::create([
            'name' => env('DATABASE_ADMIN'),
            'email' => env('DATABASE_EMAIL'),
            'password' => Hash::make(env('DATABASE_PASS')),
            'email_verified_at' => now()
        ]);

        User::factory(10)
        ->has(Customer::factory()
        ->has(Order::factory()->count(3))
        ->count(2))
        ->create();

        Model::reguard();

        Schema::enableForeignKeyConstraints();

    }
}
