<?php

use Illuminate\Database\Seeder;

class SeasonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $start = date("Y")-1;
        $count = 10;
        for($i=$start;$i<=$start+$count;$i++){
            App\Season::create([
                'season_name'=>$i.'-'.($i+1),
                'season_start'=>$i.'-09-01',
                'season_end'=>($i+1).'-09-01',
            ]);
        }
       
    }
}
