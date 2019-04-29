<?php

namespace App\Http\Controllers;

use App\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(isset($_GET['refresh'])){
            BankAccount::syncBankAccounts();
        }
        $bankaccounts = \Auth::User()->BankAccounts();
        return view('accounts.index',compact('bankaccounts'));
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
     * @param  \App\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function show(BankAccount $bankAccount)
    {
//        $payments = new \App\BankPayments();
//        $payments->setBankAccount($bankAccount);
//        if(isset($_GET['refresh'])){
//            BankAccount::syncBankAccounts();
//            $payments->syncPayments();
//            $payments->syncRequests();
//            $payments->syncRequestResponses();
//            $bankAccount->refresh();
//        }
//        $vtable = $payments->getTableConfig();
//        $money_purposes = \App\MoneyPurpose::all();
//        $money_items = \App\MoneyItem::all();
//        $seasons = \App\Season::all();
//        $currentseason_id = \App\Season::current()->id;
//        return view('accounts.payments',  compact('bankAccount','vtable','money_purposes','money_items','seasons','currentseason_id'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function edit(BankAccount $bankAccount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BankAccount $bankAccount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(BankAccount $bankAccount)
    {
        //
    }
}
