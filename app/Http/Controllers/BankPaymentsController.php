<?php

namespace App\Http\Controllers;

use App\BankPayments,App\BankAccount;
use App\Bunq;
use Illuminate\Http\Request;

class BankPaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BankAccount $bankAccount)
    {
        $payments = new \App\BankPayments();
        $payments->setBankAccount($bankAccount);
        if(isset($_GET['refresh'])){
            BankAccount::syncBankAccounts();
            //$payments->syncPayments();
            //$payments->syncRequests();
            //$payments->syncRequestResponses();
            //$bankAccount->refresh();
        }
        $vtable = $payments->getTableConfig();
        $money_purposes = \App\MoneyPurpose::all();
        $money_items = \App\MoneyItem::all();
        $seasons = \App\Season::all();
        $currentseason_id = \App\Season::current()->id;
        $tab='payments';
        return view('accounts.payments',  compact('bankAccount','vtable','money_purposes','money_items','seasons','currentseason_id','tab'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BankPayments  $bankPayments
     * @return \Illuminate\Http\Response
     */
    public function show(BankPayments $bankPayments)
    {
        //
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function data(Request $request,BankAccount $bankAccount)
    {
        //$bankAccount->getPayments();//get payments from bunq
        return \App\BankPayments::filtered($request)
                ->where('bankaccount_id', '=', $bankAccount->id)
                ->where('input_type', '=', 'payment')
                ->paginate(500);
    }

    public function pay(BankAccount $bankAccount){
        //Bunq::get()->Pay($bankAccount->external_id);
    }
    public function bunqinvoice(BankAccount $bankAccount){
        Bunq::get()->BunqInvoices($bankAccount);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BankPayments  $bankPayments
     * @return \Illuminate\Http\Response
     */
    public function edit(BankPayments $bankPayments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BankPayments  $bankPayments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BankAccount $bankAccount)
    {
        $bankPayments = new BankPayments();
        $primaryKey = $bankPayments->getKeyName();
        if($request->$primaryKey>0){
            $bankPayments = BankPayments::find($request->$primaryKey);        
            foreach($bankPayments->getFillable() as $fillable){
                if($request->$fillable=='null'){
                    $request->$fillable=null;
                }
                $bankPayments->$fillable = $request->$fillable;
            }
            $bankPayments->save();
            return ['message'=>'Wijziging is opgeslagen','status'=>'success','result'=>true ];
        }
        return ['message'=>'Onbekende betaalregel','status'=>'danger','result'=>false ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BankPayments  $bankPayments
     * @return \Illuminate\Http\Response
     */
    public function destroy(BankPayments $bankPayments)
    {
        //
    }
}
