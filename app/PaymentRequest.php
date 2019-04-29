<?php

namespace App;

use bunq\Model\Generated\Endpoint\UserCompany;
use bunq\Model\Generated\Object\NotificationFilter;
use Carbon\Carbon;
use Dirape\Token\Token;
use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    protected $bankAccount;
    protected $fillable = [
        'bankaccount_id','member_id', 'email', 'iban','status','amount','description','batch_nr','season_id'
    ];
    static function loadToken($token){
        return PaymentRequest::where('token', $token)->first();
    }
    /**
     * @param array $values ['bankaccount_id','member_id'|'email'|'iban','amount','description']
     * @return $this
     * @throws \Exception
     */
    function createRequest(array $values){
        if((double)$values['amount']<=0){
            throw new \Exception("Value 'amount' can not be zero or less");
        }
        $this->fill($values);
        $this->token = $this->createToken();
        $this->save();
        return $this;
    }
    private function createToken(){
        $token = new Token();
        return $token->unique('payment_requests','token',50);
    }
    public function setExpired(){
        $this->status = "EXPIRED";
        $this->save();
    }
    public function isExpired(){
        if($this->status != "EXPIRED" && $this->created_at->add(new \DateInterval('P' . Setting('paymentrequest_link_lifetime') . 'D'))->isPast()){
            $this->setExpired();
        }
        return ($this->status == "EXPIRED");
    }
    public function setUsed($payment=null){
        $this->status = "ACCEPTED";
        $this->save();
        if($payment!='' && $payment->getValue()->getStatus()!='CANCELLED'){
            $result = Bunq::get()->updateRequest($this->BankAccount()->external_id,$this->bunq_request_id,'CANCELLED');
        }

    }
    public function isUsed(){
        return ($this->status == "ACCEPTED");
    }
    function getAmount(){
        return MoneyFormat($this->amount + (double)Setting('payment_request_surcharge'));
    }
    function getAmountClean(){
        return MoneyFormat($this->amount);
    }
    function getShareUrl(){
        return env('APP_URL').'/paymentrequest/'.$this->token;
    }
    function getReturnUrl(){
        return env('APP_URL').'/paymentrequest/'.$this->token.'/return';
    }
    function getWebhookUrl(){
        return env('APP_URL').'/paymentrequest/'.$this->bankaccount_id.'/webhook';
    }
    function getTimeExpiry(){
        return $this->created_at->add(new \DateInterval('P'.Setting('paymentrequest_link_lifetime').'D'))->format('Y-m-d H:i:s');
    }
    function BankAccount(){
        if($this->bankAccount===null){
            $this->bankAccount = BankAccount::find($this->bankaccount_id);
        }
        return $this->bankAccount;
    }
    function checkWebhook(){
        $bankaccount = $this->BankAccount($this->bankaccount_id);
        $filters =  Bunq::get()->getAccountFilters($bankaccount->external_id);
        $filter_found=false;
        foreach((array)$filters as $index=>$filter){
            /* @var $filter NotificationFilter */

            if($filter->getNotificationDeliveryMethod()!=='URL'){
                //deze filter is niet van het type URL, dus heeft een ander doel
                continue;
            }
            if($filter->getCategory()!='BUNQME_TAB'){
                //deze filter is niet van de categorie BUNQME_TAB, dus heeft een ander doel
                continue;
            }
            if(stristr($filter->getNotificationTarget(),env('APP_URL'))===false){
                //deze filter heeft niet deze applicatie als target, dus heeft een ander doel
                continue;
            }
            //nu controleren of de target goed staat.
            if($filter->getNotificationTarget()!=$this->getWebhookUrl()) {
                //Zonee, dan de filter aanpassen en update
                unset($filters[$index]);
                continue;
            }
            $filter_found = true;

        }
        if($filter_found==false){
            $filters[] = new NotificationFilter("URL",$this->getWebhookUrl(),"BUNQME_TAB");
            Bunq::get()->updateAccountFilters($bankaccount->external_id,$filters);
        }
        //$x = Bunq::get()->getRequests($bankaccount->external_id);
        //dd($x);
        /*$x = Bunq::get()->getCashRegisters($bankaccount->external_id);
        dd($x);
        $x = Bunq::get()->createCashRegister($bankaccount->external_id,'iDEAL');
        dd($x);*/
    }

}
