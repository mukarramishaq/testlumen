<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create users using the user factory
        factory(App\User::class)->create(['email' => 'test@test.com']);
        factory(App\User::class)->create(['email' => 'test2@test.com']);
    }
}