<?php

namespace App\Console;

use App\BankAccount;
use App\Bunq;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if(is_array($_SERVER['argv'])&& isset($_SERVER['argv'][2]) && $_SERVER['argv'][2]=='--sansdaemon'){
            //speciaal voor queue workers voor het versturen van email.
            $schedule->command('queue:work')->everyMinute()->withoutOverlapping();
        }else{

            //overige cronjobs. Welke iedere minuut aangeroepen worden.
            $schedule->call(function () {
                //de eerste van de maand de Bunq factuur incasseren.
                if(Setting('invoice_account_id')>0){
                    $invoiceAccount = BankAccount::find(Setting('invoice_account_id'));
                    //dd($invoiceAccount);
                    if(!empty($invoiceAccount) && $invoiceAccount->id > 0){
                        Bunq::get()->BunqInvoices($invoiceAccount);
                    }
                }
            })->monthlyOn(1, '09:05');

            $schedule->call(function(){
                //ieder uur de transacties bijwerken
                BankAccount::syncBankAccounts();
            })->hourly();

        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
