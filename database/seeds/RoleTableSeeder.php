<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
        	[
        		'name' => 'admin',
        		'display_name' => 'Administrator',
        		'description' => 'Beheerder van deze applicatie'
        	],
        	[
        		'name' => 'user',
        		'display_name' => 'Gebruiker',
        		'description' => 'Gebruiker van deze applicate'
        	],
        	
        ];

        foreach ($roles as $key => $value) {
            Role::create($value);
        }
        DB::table('role_user')->insert([
            'user_id' => 1,
            'role_id' => 1
        ]);
    }
}
