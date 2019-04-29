<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNumberOfDebitcards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->unsignedTinyInteger('debit_cards')->default(0)->after('color');
        });
        // Insert some stuff
        DB::table('settings')->insert(
            array(
                'key' => 'debitcard_price',
                'value' => 2.00,
                'info' => 'Welk bedrag moet er voor de pinpassen in rekening worden gebracht per maand.'
            ),
            array(
                'key' => 'invoice_account_id',
                'value' =>1,
                'info' => 'Van welke bankrekening (ID) worden de bunq kosten afgeschreven'
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            //
        });
    }
}
