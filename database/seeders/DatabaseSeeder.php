<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Infrastructure\Persistence\Eloquent\Models\UserModel;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        UserModel::query()->updateOrCreate(
            ['email' => 'demo@challenge.test'],
            [
                'name' => 'Demo User',
                'password' => 'password123',
            ],
        );
    }
}
