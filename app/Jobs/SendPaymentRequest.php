<?php

namespace App\Jobs;

use App\PaymentRequest;
use bunq\Model\Generated\Endpoint\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendPaymentRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $tries = 2; //maximaal 2 keer proberen te verzenden.

    protected $member;
    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(\App\Member $member,$data=[])
    {
        //
        $this->member = $member;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $bankaccount = \App\BankAccount::find($this->data['bankaccount']);
        
        /*$request = \App\Bunq::get()->makeRequest($bankaccount->external_id,[
            'amount'=>$this->data['amount']+(double)Setting('payment_request_surcharge'),
            'currency'=>'EUR',
            'description'=>$this->data['description'],
            'recipient'=>$this->member->member_email]);
        */
        $request = new PaymentRequest();
        $request->createRequest([
            'bankaccount_id'=>$this->data['bankaccount'],
            'member_id'=>$this->member->id,
            'season_id'=>$this->data['season_id'],
            'amount'=>$this->data['amount'],
            'description'=>$this->data['description'],
            'batch_nr'=>$this->data['batch_nr'],
            ]
        );

        $paymentmail = new \App\Mail\PaymentRequest($bankaccount);
        $paymentmail->setMember($this->member);
        $paymentmail->setRequest($request);
        $paymentmail->from($this->data['from']);
        //dd($paymentmail);
        try{
            if(env('APP_DEBUG_EMAIL')){
                \Illuminate\Support\Facades\Mail::to(env('APP_DEBUG_EMAIL'))->send($paymentmail);
            }else{
                \Illuminate\Support\Facades\Mail::to($this->member->member_email)->send($paymentmail);
            }
        }catch(\Exception $e){
            die($e->getMessage());
        }
        sleep(1);

    }
    public function failed(Exception $exception)
    {
        // handle failure
        die($exception->getMessage());
    }

}
