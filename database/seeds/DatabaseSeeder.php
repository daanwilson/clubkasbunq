<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call(UsersTableSeeder::class);  
         $this->call(RoleTableSeeder::class);
         $this->call(TeamsTableSeeder::class);
         $this->call(PermissionTableSeeder::class);         
         $this->call(SettingsTableSeeder::class);         
         $this->call(MoneyPurposeTableSeeder::class);         
         $this->call(MoneyItemTableSeeder::class);         
         $this->call(SeasonTableSeeder::class);         
    }
}
