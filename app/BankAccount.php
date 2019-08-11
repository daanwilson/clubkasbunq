<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use ModelCustom;

    static function syncBankAccounts(){
        $bankaccounts = Bunq::get()->getAccounts();
        $current = parent::all(['id','external_id']);//huidige lijst met transacties
        $match = array();
        foreach($current as $c){
            $match[$c->external_id] = $c;
        }
        foreach($bankaccounts->getValue() as $account){
            $account = $account->getMonetaryAccountBank();
            if(array_key_exists($account->getId(),$match)){
                $new = $match[$account->getId()];
            }else{
                $new = new self();
            }            
            $new->description = $account->getDescription();
            $new->currency = $account->getBalance()->getCurrency();
            $new->amount = $account->getBalance()->getValue();
            $new->color = $account->getSetting()->getColor();
            $new->external_id = $account->getId();
            $new->IBAN = '';
            foreach($account->getAlias() as $alias){
                if($alias->getType()=='IBAN'){
                    $new->IBAN = $alias->getValue();
                }
            }
            $new->save();

            $payments = new BankPayments();
            $payments->setBankAccount($new);
            $payments->syncPayments();
        }
    }
    function getPayments(){
        return (new BankPayments)->getPayments($this->external_id);
    }
    function getAmountFormated(){
        return MoneyFormat($this->amount, $this->currency);
    }
    static function getByName($name){
        return parent::all()->where('description','LIKE',$name)->first();
    }
    function getBalanceBySeason(Season $season){
        //current amount;
        if($season->id > Season::current()->id){
            return 0;
        }
        $changed = BankPayments::where('bankaccount_id',$this->id)->where('season_id','>',$season->id)->sum('amount');
        return number_format($this->amount - $changed,2,'.','');
    }
}
