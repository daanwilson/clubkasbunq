<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $permission = [
            [
                'name' => 'user-management',
                'display_name' => 'Gebruikers beheer',
                'description' => 'Gebruikers aanmaken/wijzigen/verwijderen'
            ],
            [
                'name' => 'permission-management',
                'display_name' => 'Toegangsrechten beheren',
                'description' => 'Toegangsrechten aanmaken/wijzigen/verwijderen'
            ],
            [
                'name' => 'countries-management',
                'display_name' => 'Landen beheren',
                'description' => 'Landen aanmaken/wijzigen/verwijderen'
            ],
            [
                'name' => 'role-management',
                'display_name' => 'Toegangsrollen (clusters) beheren',
                'description' => 'Rollen aanmaken/wijzigen/verwijderen'
            ],
            [
                'name' => 'team-management',
                'display_name' => 'Speltakken beheren',
                'description' => 'Speltakken aanmaken/wijzigen/verwijderen'
            ],
            [
                'name' => 'settings-edit',
                'display_name' => 'Instellingen wijzigen',
                'description' => 'Alleen aanpassen van instellingen'
            ],
            [
                'name' => 'settings-management',
                'display_name' => 'Instellingen aanmaken/wijzigen/verwijderen',
                'description' => 'Instellingen aanmaken/wijzigen/verwijderen'
            ],
            [
                'name' => 'member-listing',
                'display_name' => 'Leden tonen',
                'description' => 'Tonen van leden'
            ],
            [
                'name' => 'member-management',
                'display_name' => 'Leden beheren',
                'description' => 'Leden importeren en inzien'
            ],
            [
                'name' => 'member-functions',
                'display_name' => 'Ledenrollen beheren',
                'description' => 'Ledenrollen aanmaken/wijzigen/verwijderen'
            ],
            [
                'name' => 'account-listing',
                'display_name' => 'Bankrekeningen inzien',
                'description' => 'Inzien van bankrekeningen'
            ],
            [
                'name' => 'account-payments',
                'display_name' => 'Bank betalingen doen',
                'description' => 'Mag de gebruiker betalingen doen'
            ],
            [
                'name' => 'account-payment-requests',
                'display_name' => 'Bank betaalverzoeken versturen',
                'description' => 'Mag de gebruiker betaalverzoeken inzien en versturen'
            ],
            [
                'name' => 'season-management',
                'display_name' => 'Seizoenen beheren',
                'description' => 'Aanpassen van seizoenen'
            ],
            [
                'name' => 'money-purpose-management',
                'display_name' => 'Gelddoelen beheren',
                'description' => 'Aanpassen van gelddoelen zoals zomerkamp/seizoen/weekendje'
            ],
            [
                'name' => 'money-items-management',
                'display_name' => 'Geldposten beheren',
                'description' => 'Aanpassen van geldposten contributie/materiaal/knutselfonds'
            ],
            [
                'name' => 'cash-management',
                'display_name' => 'Kleine kas beheren',
                'description' => 'Kleine kas inzien en aanpassen/beheren'
            ],
            
        ];

        foreach ($permission as $key => $value) {
            $result = Permission::create($value);
            DB::table('permission_role')->insert([
                'role_id' => 1,
                'permission_id' => $result->id,
            ]);
        }
    }

}
