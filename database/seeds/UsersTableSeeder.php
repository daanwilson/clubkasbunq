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
        DB::table('users')->insert([
            'name' => 'Daan Wilson',
            'email' => 'daanwilson@gmail.com',
            'password' => bcrypt('qwerty'),
        ]);
    }
}
