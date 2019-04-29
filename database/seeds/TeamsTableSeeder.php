<?php

use Illuminate\Database\Seeder;
use App\Team;

class TeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teams = [
        	[
        		'name' => 'Bevers',
        		'color' => 'red',
        	],
                [
        		'name' => 'Welpen',
        		'color' => 'green',
        	],
                [
        		'name' => 'Scouts',
        		'color' => 'orange',
        	],
                [
        		'name' => 'Explorers',
        		'color' => 'blue',
        	],
                [
        		'name' => 'Stam',
        		'color' => 'black',
        	],
                [
        		'name' => 'Bestuur',
        		'color' => 'purple',
        	],
        	
        	
        ];

        foreach ($teams as $key => $value) {
            Team::create($value);
        }
    }
}
