<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentRequest extends Mailable
{
    use Queueable, SerializesModels;

    protected $bankaccount;
    protected $request;
    protected $amount;
    protected $currency;
    protected $description;
    protected $member;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(\App\BankAccount $bankaccount)
    {
        $this->bankaccount = $bankaccount;
    }
    function setMember(\App\Member $member){
        $this->member=$member;
        return $this;
    }
    function setRequest($request){
        $this->request=$request;
        return $this;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject= 'Betaalverzoek';
        if($this->request && $this->request->description!=''){
            $subject.=" : ".$this->request->description;
        }
        $salutation = '';
        if($this->member){
            $salutation = $this->member->fullName().' (of ouders van)';
        }
        return $this->subject($subject)->markdown('emails.payment.request')->with([
            'salutation'=>$salutation,
            'bankaccount'=>$this->bankaccount,
            'request'=>$this->request,
            'surcharge'=>(double)Setting('payment_request_surcharge'),
        ]);
    }
}
