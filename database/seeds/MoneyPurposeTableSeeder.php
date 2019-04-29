<?php

use Illuminate\Database\Seeder;

class MoneyPurposeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [
            ['purpose_name'=>'Seizoen'],
            ['purpose_name'=>'Zomerkamp']
        ];
        foreach($arr as $a){
            \App\MoneyPurpose::create($a);
        }
    }
}
