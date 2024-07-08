<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            "user_id"           => 'ACC-00001',
            "name"              => "Admin",
            "email"             => "admin@admin.com",
            "is_admin"          => '1',
            "password"          => bcrypt("admin@admin.com"),
            "email_verified_at" => now(),
            "remember_token"    => Str::random(32)
        ]);
    }
}
