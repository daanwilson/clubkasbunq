<?php

namespace App\Http\Controllers;

use App\Member;
use App\MemberSeasonData;
use App\PaymentRequest;
use App\Season;
use function Couchbase\defaultDecoder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $vtable = (new \App\Member())->getTableConfig();
        $teams = \App\Team::all();
        $roles = \App\MemberRole::all();
        $userteams = Auth::User()->TeamIds();
        $bankaccounts = auth()->user()->BankAccounts();
        return view('members.index',  compact('vtable','teams','roles','userteams','bankaccounts'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data(Request $request)
    {
        //\DB::connection()->enableQueryLog();
        //return
        $season_id = Season::current()->id;
        $seasondata = MemberSeasonData::where("season_id",$season_id)->get();
        $memberseasondata = [];
        foreach($seasondata as $sd){
            $memberseasondata[$sd->member_id] = $sd;
        }

        $members =  Member::filtered($request)->whereHas('Seasons',function ($query) use ($season_id) {
            $query->where('id', '=', $season_id);
        })->with(['Teams','MemberRoles'])->paginate(100);
        $member_ids = [];
        foreach($members->items() as &$member){
            /* @var $member Member */
            $member->setAttribute('member_age',$member->getAge());

            $clubloten = null;
            if(array_key_exists($member->id,$memberseasondata)){
                $clubloten = $memberseasondata[$member->id]->clubloten_count;
                if($memberseasondata[$member->id]->clubloten_paid=='on'){
                    $clubloten.='<span title="Afbetaald">(*)</span>';
                }
            }else{
                //to create a season data entry.
                $member->SeasonData();
            }
            $member->setAttribute('clubloten',$clubloten);
            $member_ids[$member->id] = $member->id;
        }
        $paymentrequests = PaymentRequest::where('season_id','=',Season::current()->id)->whereIn('member_id',$member_ids)->orderBy('status', 'DESC')->get();
        $requestArray=array();
        foreach($paymentrequests as $pr){
            $requestArray[$pr->member_id][] = $pr;
        }
        foreach($members->items() as &$member){
            /* @var $member Member */
            if(array_key_exists($member->id,$requestArray)){
                $member->setAttribute('payment_requests',(array)$requestArray[$member->id]);
            }else{
                $member->setAttribute('payment_requests',array());
            }

        }
        return $members;
        //return \DB::getQueryLog();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function import()
    {
        $member = new \App\Member();
        $season = \App\Season::current();
        return view('members.import',  compact('member','season'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        try{
            Member::importXML($request->upload_file,$request->members_not_present);
            return ['message'=>'Leden zijn succesvol ingelezen','status'=>'success','result'=>false ]; 
        }catch(\Exception $e){
           return ['message'=>$e->getMessage(),'file'=>$e->getFile().':'.$e->getLine(),'status'=>'danger','result'=>false ]; 
        }
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function clubloten()
    {
        $member = new \App\Member();
        $season = \App\Season::current();
        return view('members.clubloten',  compact('member','season'));
    }
    public function clublotenImport(Request $request){
        try{
            $errors = Member::importClubloten($request->upload_file);
            $message = 'Clubloten zijn succesvol ingelezen';
            if(count($errors)>0){
                $error ='Onderstaande verkopers konden niet worden gevonden tussen de leden. Deze dienen handmatig te worden ingevoerd.';
                $error.='<div><ul>';
                foreach($errors as $e){
                    $error.='<li>'.$e.'</li>';
                }
                $error.='</ul></div>';
            }
            return ['message'=>$message,'status'=>'success','result'=>false ,'data'=>['upload_result'=>$error]];
        }catch(\Exception $e){
            return ['message'=>$e->getMessage(),'file'=>$e->getFile().':'.$e->getLine(),'status'=>'danger','result'=>false ];
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $member = new \App\Member();
        return view('members.edit',  compact('member'));
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
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function show(Member $member)
    {
        return view('members.show',  compact('member'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function edit(Member $member)
    {
        return view('member.edit',  compact('member'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Member $member)
    {
        //
        //if($request->clubloten_count!==null){
            $sd = $member->SeasonData();
            $sd->clubloten_count = $request->clubloten_count;
            $sd->clubloten_paid = ($request->clubloten_paid=='on' || $request->clubloten_paid==true ? 'on'  :null);
            $sd->save();
        //}
        return ['message'=>'Lid is opgeslagen','status'=>'success','result'=>true ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function destroy(Member $member)
    {
        $member->delete();
        return ['message'=>'Lid is verwijderd','status'=>'danger','result'=>true ];
    }
    
    public function action(Request $request){
       try{
        if($request->action=='send_request'){
             $ids = explode(",",$request->ids);
             if(count($ids)==0){
                 throw new \Exception("Geen ids geselecteerd/meegestuurd.");
             }
             if((int)$request->bankaccount==0){
                 throw new \Exception("Geen bankrekening geselecteerd.");
             }
             if($request->description==''){
                 throw new \Exception("Geen omschrijving ingevuld.");
             }
             if((double)$request->amount<0){
                 throw new \Exception("Geen bedrag ingevuld.");
             }
             //we gaan hier a.h.v. de member ids een betaalverzoek versturen.
             $members = Member::find($ids);
             $batch_nr = (int)PaymentRequest::select('batch_nr')->max('batch_nr') + 1;
             foreach($members as $member){
                 //$member->sendPaymentRequest($request->amount,$request->bankaccount,$request->description);
                 $this->dispatch(new \App\Jobs\SendPaymentRequest($member,[
                     'amount'=>$request->amount,
                     'bankaccount'=>$request->bankaccount,
                     'description'=>$request->description,
                     'batch_nr'=>$batch_nr,
                     'season_id'=>Season::current()->id,
                     'from'=>Auth::user()->email,
                 ]));

                 //sleep(1);//200ms pauze tussen iedere request.
             }
             return ['message'=>'Betaalverzoek is succesvol verstuurd aan '.count($ids).' leden.','status'=>'success','result'=>true,'hide_modal'=>true ];
        }else{
            throw new \Exception("Onbekende actie.....");
        }
       }catch(\Exception $e){
           return ['message'=>$e->getMessage(),'status'=>'danger','result'=>false ];
       }
    }
}
