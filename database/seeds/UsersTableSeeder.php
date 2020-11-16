<?php
use Illuminate\Database\Seeder;

use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            'name' => 'haidi',
            'password' => app('hash')->make('samarinda'),
        ]);
    }
}
