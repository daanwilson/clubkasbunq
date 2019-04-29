<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankPayments extends Model
{
    use ModelCustom;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'purpose_id', 'item_id','season_id'
    ];
    
    
    protected $bankAccount;
    function setBankAccount(BankAccount $bankAccount){
        $this->bankAccount=$bankAccount;
    }
    function getTableConfig(){
        return [
            'src'=>route('account.payments.data',($this->bankAccount ? $this->bankAccount->id : 0)),
            'src_requests'=>route('account.requests.data',($this->bankAccount ? $this->bankAccount->id : 0)),
            'primary_key'=>'id',
            'view'=>false,
            'edit'=>false,
            //'edit_url'=>route('account.payment.edit',null),
            'remove'=>false,            
            //'remove_url'=>route('account.payment.delete',null),
            
            'search'=>[
                'id'=>['operator'=>'=','value'=>'%s'],
                'description'=>['operator'=>'like','value'=>'%%%s%%'],
                'counterpart_IBAN'=>['operator'=>'like','value'=>'%%%s%%'],
                'counterpart_name'=>['operator'=>'like','value'=>'%%%s%%'],
                //'color'=>array('operator'=>'like','value'=>'%%%s%%'),
            ],
            'order'=>'created|desc',
            'filter'=>['season_id'=>[null,0]],
            
        ];
    }
    function syncPayments(){
        //BankAccount::syncBankAccounts();
        $options = [
            "count"=> 100,
            "newer_id"=>BankPayments::where('bankaccount_id',$this->bankAccount->id)->max('external_id')
            ];
        $payments = Bunq::get()->getPayments($this->bankAccount->external_id,$options);
        //dd($payments);
        $current = parent::where('bankaccount_id',$this->bankAccount->id)->where('input_type','payment')->orderBy('id','DESC')->get(['id','external_id']);
        $match = array();
        foreach($current as $c){
            $match[$c->external_id] = $c;
        }
        foreach($payments->getValue() as $payment){
            //dd($payment);
            if(array_key_exists($payment->getId(),$match)){
                $new = $match[$payment->getId()];
            }else{
                $new = new self();
            }            
            $new->bankaccount_id = $this->bankAccount->id;
            $new->external_bankaccount_id = $payment->getMonetaryAccountId();
            $new->external_id = $payment->getId();
            $new->description = $payment->getDescription();
            $new->currency = $payment->getAmount()->getCurrency();
            $new->amount = $payment->getAmount()->getValue();
            $new->created = $this->getcorrectDate($payment->getCreated());
            $new->updated = $this->getcorrectDate($payment->getUpdated());
            $new->counterpart_IBAN = $payment->getCounterpartyAlias()->getIban();
            $new->counterpart_name = $payment->getCounterpartyAlias()->getDisplayName();
            $new->batch_id = $payment->getBatchId();
            $new->type = $payment->getType();
            $new->subType = $payment->getSubType();
            $new->input_type = 'payment';
            $new->save();
        }
    }
    
    function syncRequests(){
        //BankAccount::syncBankAccounts();
        /*$options = [
            "count"=> 100,
            //"newer_id"=>BankPayments::where('bankaccount_id',$this->bankAccount->id)->max('external_id')
            ];
        $requests = Bunq::get()->getRequests($this->bankAccount->external_id,$options);
        $current = parent::where('bankaccount_id',$this->bankAccount->id)
            ->where('input_type','tab_request')
            ->orderBy('id','DESC')
            ->take(100)
            ->get(['id','external_id']);

        $match = array();
        foreach($current as $c){
            $match[$c->external_id] = $c;
        }
        //dd($requests);
        foreach($requests->getValue() as $payment){
            //dd($payment);
            if(array_key_exists($payment->getId(),$match)){
                $new = $match[$payment->getId()];
            }else{
                $new = new self();
            }            
            $new->bankaccount_id = $this->bankAccount->id;
            $new->external_bankaccount_id = $payment->getMonetaryAccountId();
            $new->external_id = $payment->getId();
            $new->description = $payment->getBunqmeTabEntry()->getDescription();
            $new->currency = $payment->getBunqmeTabEntry()->getAmountInquired()->getCurrency();
            $new->amount = $payment->getBunqmeTabEntry()->getAmountInquired()->getValue();
            $new->created = $this->getcorrectDate($payment->getCreated());
            $new->updated = $this->getcorrectDate($payment->getUpdated());
            //$new->counterpart_IBAN = $payment->getCounterpartyAlias()->getIban();
            //$new->counterpart_name = $payment->getCounterpartyAlias()->getDisplayName();
            //$new->batch_id = $payment->getBatchId();
            //$new->type = $payment->getType();
            //$new->subType = $payment->getSubType();
            $new->input_type = 'tab_request';
            $new->status = $payment->getStatus();
            $new->share_url = $payment->getBunqmeTabShareUrl();
            $new->expire = $payment->getTimeExpiry();
            $new->save();
        }


        $options = [
            "count"=> 100,
            //"newer_id"=>BankPayments::where('bankaccount_id',$this->bankAccount->id)->max('external_id')
        ];
        $requests = Bunq::get()->getPayRequests($this->bankAccount->external_id,$options);
        $current = parent::where('bankaccount_id',$this->bankAccount->id)
            ->where('input_type','request')
            ->orderBy('id','DESC')
            ->take(100)
            ->get(['id','external_id']);

        $match = array();
        foreach($current as $c){
            $match[$c->external_id] = $c;
        }
        //dd($requests);
        foreach($requests->getValue() as $payment){
            //dd($payment);
            if(array_key_exists($payment->getId(),$match)){
                $new = $match[$payment->getId()];
            }else{
                $new = new self();
            }
            $new->bankaccount_id = $this->bankAccount->id;
            $new->external_bankaccount_id = $payment->getMonetaryAccountId();
            $new->external_id = $payment->getId();
            $new->description = $payment->getDescription();
            $new->currency = $payment->getAmountInquired()->getCurrency();
            $new->amount = $payment->getAmountInquired()->getValue();
            $new->created = $this->getcorrectDate($payment->getCreated());
            $new->updated = $this->getcorrectDate($payment->getUpdated());
            $new->counterpart_IBAN = $payment->getCounterpartyAlias()->getIban();
            $new->counterpart_name = $payment->getCounterpartyAlias()->getDisplayName();
            $new->batch_id = $payment->getBatchId();
            //$new->type = $payment->getType();
            //$new->subType = $payment->getSubType();
            $new->input_type = 'request';
            $new->status = $payment->getStatus();
            $new->share_url = $payment->getBunqmeShareUrl();
            $new->expire = $payment->getTimeExpiry();
            $new->save();
        }*/
    }
    function syncRequestResponses(){
        //BankAccount::syncBankAccounts();
        /*$options = [
            "count"=> 100,
            //"newer_id"=>BankPayments::where('bankaccount_id',$this->bankAccount->id)->max('external_id')
            ];
        $requests = Bunq::get()->getRequestsResponse($this->bankAccount->external_id,$options);
        //dd($requests);
        $current = parent::where('bankaccount_id',$this->bankAccount->id)
            ->where('input_type','request_response')
            ->orderBy('id','DESC')
            ->take(100)
            ->get(['id','external_id']);
        $match = array();
        foreach($current as $c){
            $match[$c->external_id] = $c;
        }
        foreach($requests->getValue() as $payment){
            //dd($payment);
            if(array_key_exists($payment->getId(),$match)){
                $new = $match[$payment->getId()];
            }else{
                $new = new self();
            }            
            $new->bankaccount_id = $this->bankAccount->id;
            $new->external_bankaccount_id = $payment->getMonetaryAccountId();
            $new->external_id = $payment->getId();
            $new->description = $payment->getDescription();
            $new->currency = $payment->getAmountInquired()->getCurrency();
            $new->amount = $payment->getAmountInquired()->getValue();
            $new->created = $this->getcorrectDate($payment->getCreated());
            $new->updated = $this->getcorrectDate($payment->getUpdated());
            $new->counterpart_IBAN = $payment->getCounterpartyAlias()->getIban();
            $new->counterpart_name = $payment->getCounterpartyAlias()->getDisplayName();
            //$new->batch_id = $payment->getBatchId();
            $new->type = $payment->getType();
            $new->subType = $payment->getSubtype();
            $new->input_type = 'request_response';
            $new->status = $payment->getStatus();
            //$new->share_url = $payment->getBunqmeShareUrl();
            $new->expire = $payment->getTimeExpiry();
            $new->save();
        }*/
    }
    function getAmountFormated(){
        return MoneyFormat($this->amount,$this->currency);
    }
    function getcorrectDate($date){
        $dt_obj = new \DateTime($date ." UTC");
        $dt_obj->setTimezone(new \DateTimeZone(\Config::get('app.timezone')));
        return $dt_obj->format('Y-m-d H:i:s');
    }
}
