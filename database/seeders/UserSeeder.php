<?php

namespace Database\Seeders;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->truncate();

        $admin = User::factory(1, ['email' => 'admin@admin.com'])->create()->first();
        $admin->syncRoles(Roles::ADMIN->value);

        $moder = User::factory(1, ['email' => 'moder@moder.com'])->create()->first();
        $moder->syncRoles(Roles::MODERATOR->value);

        User::factory(5)->create();
    }
}
