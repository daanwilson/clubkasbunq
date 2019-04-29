<?php

namespace App\Http\Controllers;

use App\Bunq;
use App\BunqTabRequests;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BunqTabRequestsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vtable = (new BunqTabRequests())->getTableConfig();
        return view('bunqtabs.index',compact('vtable'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data(Request $request)
    {
        $data = \App\BunqTabRequests::filtered($request)->get();
        //dd($data);
        foreach($data as &$d) {
            /* @var $member Member */
            $d->setAttribute('account', $d->Account());
        }
        //dd($data);
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $tab = new BunqTabRequests();
        $accounts = \Auth::User()->BankAccounts();
        return view('bunqtabs.edit',compact('tab','accounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BunqTabRequests $tab, Request $request)
    {

        $this->validate($request, [
            'amount' => 'required',
            'description' => 'required',
        ]);
        $tab->AccountId = $request->input('AccountId');
        $tab->amount = makeAmount($request->input('amount'));
        $tab->description = $request->input('description');
        $tab->expires = $request->input('expires');
        $tab->shortname = $request->input('shortname');
        $tab->save();
        return ['message'=>'Betaalverzoek succesvol aangemaakt.','status'=>'success','result'=>true,'data'=>$tab,'redirect'=>config('app.url').'/bunqtab/'.$tab->id];

        /*return redirect()->route('roles.index')
                        ->with('success','Rol succesvol aangemaakt.');*/
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BunqTabRequests  $bunqTabRequests
     * @return \Illuminate\Http\Response
     */
    public function show(BunqTabRequests $bunqTabRequests)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BunqTabRequests  $bunqTabRequests
     * @return \Illuminate\Http\Response
     */
    public function edit(BunqTabRequests $tab)
    {
        $accounts = \Auth::User()->BankAccounts();
        return view('bunqtabs.edit',compact('tab','accounts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BunqTabRequests  $bunqTabRequests
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BunqTabRequests $tab)
    {
        $this->validate($request, [
            'amount' => 'required',
            'description' => 'required',
        ]);
        $tab->AccountId = $request->input('AccountId');
        $tab->amount = makeAmount($request->input('amount'));
        $tab->description = $request->input('description');
        $tab->expires = $request->input('expires');
        $tab->shortname = $request->input('shortname');
        $tab->save();
        return ['message'=>'Betaalverzoek succesvol opgeslagen.','status'=>'success','result'=>true,'data'=>$tab];

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BunqTabRequests  $bunqTabRequests
     * @return \Illuminate\Http\Response
     */
    public function destroy(BunqTabRequests $tab)
    {

        $tab->delete();
        return ['message'=>'Betaalverzoek is verwijderd','status'=>'danger','result'=>true ];
    }

    public function qrcode(BunqTabRequests $tab,string $shortname=null){
        return QrCode::format('png')
            ->size(300)
            ->merge('/public_html/'.basename(env('APP_LOGO')),.3)
            ->errorCorrection('H')
            ->generate($tab->getUrl());
    }

    public function handle($shortname){
        //dd($shortname);\
        try{
            if(empty($shortname)){
                throw new \Exception('Geen betalingsnaam meegegeven');
            }
            $tab = BunqTabRequests::where('shortname',$shortname)->first();
            if(empty($tab) || $tab->id==0){
                throw new \Exception('Betaling niet gevonden');
            }
            if($tab->isExpired()){
                throw new \Exception('Betaling is verlopen');
            }
            $request = null;
            if($tab->bunq_tab_id>0){
                $request = Bunq::get()->getRequest($tab->Account()->external_id, $tab->bunq_tab_id);
                if($request->getValue()->getStatus() != 'WAITING_FOR_PAYMENT'){
                    $request = null;
                }
            }

            if(empty($request)){
                $request = \App\Bunq::get()->makeRequest($tab->Account()->external_id, [
                    'amount' => $tab->amount,
                    'currency' => 'EUR',
                    'description' => $tab->description,
                    'redirectUrl'=>$tab->getReturnUrl(),
                ]);
            }
            if(!empty($request) && $request->getValue()->getId()>0){
                $tab->bunq_tab_id= $request->getValue()->getId();
                $tab->save();
                return redirect($request->getValue()->getBunqmeTabShareUrl());
                die;
            }
            throw new \Exception('Onbekende fout opgetreden.');
        }catch (\Exception $e){
            $error = $e->getMessage();
            return view('bunqtabs.error', compact('error'));
        }
    }
    function Payed(){
        return view('bunqtabs.success');
    }
}
