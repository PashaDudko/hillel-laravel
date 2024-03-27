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

        User::factory(1, ['email' => 'admin@admin.com'])->create()->syncRoles(Roles::ADMIN->value);
        /**
         *  TO FIX
         * Method Illuminate\Database\Eloquent\Collection::assignRole does not exist.
 */
        User::factory(1, ['email' => 'moder@moder.com'])->create()->syncRoles(Roles::MODERATOR->value);
        User::factory(5)->create();
    }
}
