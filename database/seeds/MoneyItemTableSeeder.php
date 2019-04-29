<?php

use Illuminate\Database\Seeder;
use App\MoneyItem;

class MoneyItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [
            ['item_name'=>'Contributie'],
            ['item_name'=>'Kampgeld'],
            ['item_name'=>'Weekendgeld'],
            ['item_name'=>'Kaartgeld'],
            ['item_name'=>'Sponsoring'],
            ['item_name'=>'Clubactie'],
            ['item_name'=>'Lenteactie'],
            ['item_name'=>'Vriendenloterij'],
            ['item_name'=>'Overige acties'],
            ['item_name'=>'Uniformen'],
            ['item_name'=>'Onderhoud clubgebouw (clubactie)'],
            ['item_name'=>'Huur kampterrein'],
            ['item_name'=>'Huur clubgebouw'],
            ['item_name'=>'Overvliegen'],
            ['item_name'=>'Nieuwjaarsvieringen'],
            ['item_name'=>'Sinterklaas'],            
            ['item_name'=>'Kerstmis'], 
            ['item_name'=>'Overige vieringen'],
            ['item_name'=>'Fourage (Eten/drinken)'],
            ['item_name'=>'Vervoer'],
            ['item_name'=>'Workshops'],            
            ['item_name'=>'Uitjes'],            
            ['item_name'=>'Bankkosten'],
            ['item_name'=>'Overige'],
        ];
        foreach($arr as $a){
            \App\MoneyItem::create($a);
        }
    }
}
