<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = auth()->user()->Teams();
        $cash = auth()->user()->CashAccounts();
        $cash_total = 0;
        foreach($cash as $c){
            $cash_total+=$c->getAmount();
        }

        $bankaccounts = auth()->user()->BankAccounts();
        $bankcount = count($bankaccounts);
        $bankamount = 0;
        foreach($bankaccounts as $account){
            $bankamount+= $account->amount;
        }
        return view('home',compact('teams','cash','cash_total','bankcount','bankamount'));
    }
}
