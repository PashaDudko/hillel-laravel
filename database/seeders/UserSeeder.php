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
        $admin = User::factory(1, ['name' => 'Admin ','lastname' => 'Admin','email' => 'admin@admin.com'])->create()->first();
        $admin->syncRoles(Roles::ADMIN->value);

        $moder = User::factory(1, ['name' => 'Moderator ','lastname' => 'Moderator','email' => 'moder@moder.com'])->create()->first();
        $moder->syncRoles(Roles::MODERATOR->value);

        User::factory(5)->create();
    }
}
