<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\Bunq;
use App\Member;
use App\PaymentRequest;
use bunq\Model\Generated\Endpoint\BunqMeTab;
use bunq\Model\Generated\Endpoint\BunqMeTabResultInquiry;
use bunq\Model\Generated\Endpoint\BunqResponseCardGeneratedCvc2;
use bunq\Model\Generated\Endpoint\MonetaryAccountBank;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function handle($token)
    {
        $token = PaymentRequest::where('token', $token)->first();
        if (empty($token)) {
            $title = "Betaallink niet gevonden";
            $error = "Sorry, de betaallink is niet gevonden. Excuses voor het ongemak.";
            return view('paymentrequests.error',compact('title','error'));
        }
        $bankaccount = $token->BankAccount();
        if ($token->bunq_request_id > 0) {
            $request = Bunq::get()->getRequest($bankaccount->external_id, $token->bunq_request_id);
            if($request->getValue()->getStatus()=='EXPIRED'){
                $token->setExpired();
            }elseif($request->getValue()->getStatus()=='CANCELLED' || count($request->getValue()->getResultInquiries())>0){
                $token->setUsed();
            }
        }
        if ($token->isExpired()) {
            $title= "Betaallink verlopen";
            $error = "Sorry, de betaallink is verlopen. Excuses voor het ongemak.";
            return view('paymentrequests.error',compact('title','error'));
        }
        if ($token->isUsed()) {
            $title= "Betaallink is gesloten";
            $error = "Sorry, deze betaling is al voldaan of geannuleerd.";
            return view('paymentrequests.error',compact('title','error'));
        }
        try {
            $token->checkWebhook();
            if(empty($token->bunq_request_id)){
                //$member = Member::find($token->member_id);
                $request = \App\Bunq::get()->makeRequest($bankaccount->external_id, [
                    'amount' => $token->amount + (double)Setting('payment_request_surcharge'),
                    'currency' => 'EUR',
                    'description' => $token->description,
                    'redirectUrl'=>$token->getReturnUrl(),
                    //'recipient' => $member->member_email
                    //'recipient' => 'betaalverzoeken@hescohonk.nl',
                    //'recipient' => 'maryellen.bowie@bunq.nl'
                ]);
                if($request->getValue()->getId()>0){
                    //PaymentRequest::where('batch_nr',$token->batch_nr)->update(['bunq_request_id' => $request->getId(),'bunq_share_url'=>$request->getBunqmeTabShareUrl()]);
                    $token->bunq_request_id=$request->getValue()->getId();
                    $token->bunq_share_url=$request->getValue()->getBunqmeTabShareUrl();
                    $token->save();
                }
            }
            //dd($request);
            if ($token->bunq_share_url) {
                return redirect($token->bunq_share_url);
            }
            $title= "Betaallink verlopen";
            $error = "Sorry, de betaallink is verlopen. Excuses voor het ongemak.";
            return view('paymentrequests.error', compact('error','title','token', 'request', 'bankaccount'));
        }catch (\Exception $error){
            $title="Error";
            $error = $error->getMessage();
            return view('paymentrequests.error', compact('title','error'));
        }

    }
    public function returned($token){
        //we moeten hier een andere pagina tonen.
        //Als betaling voldaan is, dan een bedankt scherm, anders een 'Probeer opnieuw.'
        $token = PaymentRequest::where('token', $token)->first();
        if(!empty($token)){
            $bankaccount = $token->BankAccount();
            if ($token->bunq_request_id > 0) {
                $request = Bunq::get()->getRequest($bankaccount->external_id, $token->bunq_request_id);
                if(count($request->getValue()->getResultInquiries())>0){
                    $token->setUsed($request);
                    $title="Betaling succesvol";
                    return view('paymentrequests.success',compact('title'));
                }else{
                    $title="Betaling mislukt";
                    $link = $token->getShareUrl();
                    return view('paymentrequests.retry',compact('title','link'));
                }
            }
        }
        return $this->handle($token);
    }
    public function webhook(BankAccount $bankaccount){
        $result = \request()->get('NotificationUrl');
        $bunqMeTabId = (int)$result['object']['BunqMeTabResultInquiry']['bunq_me_tab_id'];
        if($bunqMeTabId==0){
            throw new \Exception("Unknown Bunq Tab ID");
        }
        $request = Bunq::get()->getRequest($bankaccount->external_id,$bunqMeTabId);
        if(count($request->getValue()->getResultInquiries())>0){
            //deze bunq me tab heeft een betaling, dus de token op Used zetten
            //en de TAB op CANCELLED zetten
            $token = PaymentRequest::where('bankaccount_id', $bankaccount->id)->where('bunq_request_id',$bunqMeTabId)->first();
            if(empty($token)){
                throw new \Exception("Unknown Bunq Tab Request");
            }
            $token->setUsed($request);
        }
        die('stop');
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PaymentRequest $paymentRequest
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentRequest $paymentRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PaymentRequest $paymentRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentRequest $paymentRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\PaymentRequest $paymentRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentRequest $paymentRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PaymentRequest $paymentRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentRequest $paymentRequest)
    {
        //
    }
}
