<?php

namespace App;

use App\ModelCustom;
use function Couchbase\defaultDecoder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Mail\PaymentRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class Member extends Model
{

    use SoftDeletes;
    use ModelCustom;

    protected $dates = ['member_birthdate', '', 'deleted_at'];

    protected $fillable = [ //the key numbers are for the import
        0 => 'member_team',
        1 => 'member_id',
        2 => 'member_initials',
        3 => 'member_lastname',
        4 => 'member_insertion',
        5 => 'member_firstname',
        8 => 'member_street',
        9 => 'member_number',
        10 => 'member_number_addition',
        11 => 'member_zipcode',
        12 => 'member_place',
        13 => 'member_country_id',
        14 => 'member_birthdate',
        15 => 'member_birthplace',
        16 => 'member_birthcountry_id',
        17 => 'member_gender',
        18 => 'member_phone',
        19 => 'member_mobile',
        20 => 'member_email',
        21 => 'member_parent1_name',
        22 => 'member_parent1_phone',
        23 => 'member_parent1_email',
        24 => 'member_parent2_name',
        25 => 'member_parent2_phone',
        26 => 'member_parent2_email',
        27 => 'member_function',
        28 => 'member_function_startdate',
        29 => 'member_health_insurance_company',
        30 => 'member_health_insurance_number',
        31 => 'member_additional_info',
    ];

    function MemberRoles()
    {
        return $this->belongsToMany('\App\MemberRole', 'member_member_roles', 'member_id', 'role_id');
    }

    function Teams()
    {
        return $this->belongsToMany('\App\Team', 'member_teams', 'member_id', 'team_id');
    }

    function Seasons()
    {
        return $this->belongsToMany('\App\Season', 'member_seasons', 'member_id', 'season_id');
    }

    function SeasonData($season_id = null)
    {
        if ($season_id === null) {
            $season_id = Season::current()->id;
        }
        $result = $this->hasOne('\App\MemberSeasonData')->where('season_id', '=', $season_id)->first();
        if (empty($result)) {
            $result = new MemberSeasonData();
            $result->season_id = $season_id;
            $result->member_id = $this->id;
            $result->save();
        }
        return $result;
    }

    function Country()
    {
        return $this->belongsTo('\App\Country', 'member_country_id');
    }

    function BirthCountry()
    {
        return $this->belongsTo('\App\Country', 'member_birthcountry_id');
    }

    function fullLastName()
    {
        return trim($this->member_insertion . ' ' . $this->member_lastname);
    }

    function fullName()
    {
        return $this->member_firstname . ' ' . $this->fullLastName();
    }

    function getAge()
    {
        return $this->member_birthdate->age;
    }

    function getTableConfig()
    {
        return array(
            'src' => route('members.data'),
            'primary_key' => 'id',
            'edit' => false,
            'view' => (\Auth::User()->can('member-listing')),
            'edit_url' => '/member',
            'remove' => (\Auth::User()->can('member-management')),
            'remove_url' => '/member',

            'search' => array(
                'id' => array('operator' => '=', 'value' => '%s'),
                'member_id' => array('operator' => '=', 'value' => '%s'),
                'member_firstname' => array('operator' => 'like', 'value' => '%%%s%%'),
                'member_lastname' => array('operator' => 'like', 'value' => '%%%s%%'),
                'member_email' => array('operator' => 'like', 'value' => '%%%s%%'),
            ),

        );
    }

    function sendPaymentRequest($amount, $bankaccount_id, $description)
    {
        $bankaccount = \App\BankAccount::find($bankaccount_id);

        /*$request = \App\Bunq::get()->makeRequest($bankaccount->external_id,[
            'amount'=>$amount+(double)Setting('payment_request_surcharge'),
            'currency'=>'EUR',
            'description'=>$description,
            'recipient'=>$this->member_email]);*/

        $request = new PaymentRequest();
        $request->createRequest(['bankaccount_id' => $bankaccount_id, 'member_id' => $this->id, 'season_id' => \App\Season::current()->id, 'amount' => $amount, 'description' => $description]);

        $paymentmail = new \App\Mail\PaymentRequest($bankaccount);
        $paymentmail->setMember($this);
        $paymentmail->setRequest($request);
        $paymentmail->from(Auth::user()->email);
        //dd($paymentmail);
        try {
            \Illuminate\Support\Facades\Mail::to($this->member_email)->send($paymentmail);
        } catch (\Exception $e) {
            dd($e);
        }
    }
    static function importXML($file,$remove_members_not_present=false){

        if(!isset($file) || !is_array($file) || $file['size']==0 || empty($file['content'])){
            throw new \Exception("Geen bestand geupload of bestand is leeg");
        }
        if(stristr($file['type'],'xml')===false){
            throw new \Exception("Bestand is geen XML formaat.");
        }
        $remove_members_not_present = ($remove_members_not_present==true || $remove_members_not_present=='true' || $remove_members_not_present==1 ? true : false);
        MembersImport::import($file, $remove_members_not_present);

        return ['message'=>'Bestand succesvol ingelezen','status'=>'success','result'=>true ];
    }
    static function importClubloten($file)
    {
        if (!isset($file) || !is_array($file) || $file['size'] == 0 || empty($file['content'])) {
            throw new \Exception("Geen bestand geupload of bestand is leeg");
        }
        if (stristr($file['type'], 'xml') === false) {
            throw new \Exception("Bestand is geen XML formaat.");
        }
        return MembersImport::clubloten($file);

        return ['message' => 'Bestand succesvol ingelezen', 'status' => 'success', 'result' => true];
    }

    protected static function extractFilters($query)
    {
        $filtersArr = ModelCustom::extractFilters($query);
        if (array_key_exists('clubloten', $filtersArr)) {
            $count = $filtersArr['clubloten'][0];

            $result = MemberSeasonData::where('season_id', Season::current()->id)->where(function ($query) use ($count) {
                if($count<20 && $count!=1) {
                    $query->where('clubloten_count', '<=', $count)->orWhereNull('clubloten_count');
                }else{
                    $query->where('clubloten_count', '>=', $count);
                }
            })->get();
            $member_ids = [-1];
            foreach ($result as $r) {
                $member_ids[] = $r->member_id;
            }
            unset($filtersArr['clubloten']);
            $filtersArr['id'] = $member_ids;

        }
        return $filtersArr;
    }
    protected static function addSorting(Builder $builder, string $sorting) {
        $sorting = explode(",", $sorting);

        foreach ($sorting as $sort) {
            if ($sort != '') {
                $parts = explode("|", $sort);
                if($parts[0]=='clubloten'){
                    $result = MemberSeasonData::where('season_id', Season::current()->id)->orderBy('clubloten_count',$parts[1])->get(['member_id']);
                    $member_ids = [];
                    foreach($result as $r){
                        $member_ids[] = $r->member_id;
                    }
                    $parts[0] = "FIELD(members.id,".implode(",",$member_ids).")";
                    $builder->orderByRaw($parts[0]." ". $parts[1]);
                }else{
                    $builder->orderBy($parts[0], $parts[1]);
                }

            }
        }
        return $builder;
    }

}

class MembersImport
{

    static function import($file, $remove_members_not_present = false)
    {
        $xml = static::getFileContent($file);
        $arr = static::ParseXml($xml);
        $memberlist = static::convertToMemberlist($arr);
        static::importMembers($memberlist, $remove_members_not_present);
    }

    static function getFileContent($file)
    {
        $data = substr($file['content'], strpos($file['content'], ','));
        $data = base64_decode($data);
        if ($data == false) {
            throw new \Exception("Bestand is geen geldig XML bestand.");
        }
        return $data;
    }

    static function parseXml($xml)
    {

        $xml = simplexml_load_string($xml);
        $json = json_encode($xml);
        return json_decode($json, true);
    }

    static function convertToMemberlist($arr)
    {
        if (!is_array($arr)) {
            throw new \Exception("Bestand is geen geldig XML bestand.");
        }
        if (!array_key_exists('Worksheet', $arr) || !array_key_exists('Table', $arr['Worksheet']) || !array_key_exists('Row', $arr['Worksheet']['Table'])) {
            throw new \Exception("Bestand heeft een onbekende indeling.");
        }
        $records = $arr['Worksheet']['Table']['Row'];
        if (!is_array($records) || count($records) <= 1) {
            throw new \Exception("Bestand bevat geen leden.");
        }
        unset($records[0]);//eerste regel zijn de kopjes.
        $colums = (new Member())->getFillable();
        $result = array();
        //$records = array_slice($records,0,20);
        foreach ($records as $record) {
            if (isset($record['Cell']) && is_array($record['Cell'])) {

                $role = ['function' => '', 'startdate' => '', 'team' => ''];
                $team = null;
                $member = [];
                foreach ($record['Cell'] as $i => $cell) {
                    if (isset($colums[$i])) {
                        if (is_array($cell['Data'])) {
                            $member[$colums[$i]] = '';
                        } else {
                            if ($colums[$i] == 'member_team') {
                                $team = $cell['Data'];
                                $role['team'] = $cell['Data'];
                            } elseif ($colums[$i] == 'member_function') {
                                $role['function'] = $cell['Data'];
                            } elseif ($colums[$i] == 'member_function_startdate') {
                                $role['startdate'] = Custom::String($cell['Data'])->getDateTime()->format('Y-m-d');
                            } else {

                                if (in_array($colums[$i], (new Member())->getDates())) {
                                    $cell['Data'] = Custom::String($cell['Data'])->getDateTime()->format('Y-m-d');
                                }
                                if ($colums[$i] == 'member_gender') {
                                    $cell['Data'] = Custom::String($cell['Data'])->getGender();
                                }
                                $member[$colums[$i]] = $cell['Data'];
                            }
                        }
                    }
                }
                if (array_key_exists($member['member_id'], $result)) {
                    $member['member_team'] = $result[$member['member_id']]['member_team'];
                    $member['member_team'][] = $team;
                    $member['member_function'] = $result[$member['member_id']]['member_function'];
                    $member['member_function'][] = $role;
                } else {
                    $member['member_team'] = array($team);
                    $member['member_function'] = array($role);
                }
                $result[$member['member_id']] = $member;
            }
        }
        return $result;
    }

    static function importMembers($members, $remove_members_not_present = false)
    {
        if (is_array($members)) {
            //print_r($members);
            $current = Member::withTrashed()->get();
            $current = Custom::setKey($current, 'member_id');
            $current_season_id = Season::current()->id;
            foreach ($members as $member) {
                $teams = array_unique($member['member_team']);
                $roles = $member['member_function'];
                unset($member['member_team']);
                unset($member['member_function']);

                $member['member_country_id'] = Country::getIdByField($member['member_country_id'], 'country_name');
                $member['member_birthcountry_id'] = Country::getIdByField($member['member_birthcountry_id'], 'country_name');

                $teamids = [];
                $roleids = [];
                foreach ($teams as $team) {
                    $teamids[] = Team::getIdByField($team, 'name');
                }
                foreach ($roles as $role) {
                    $id = MemberRole::getIdByField($role['function'], 'role_name');
                    $roleids[$id] = ['role_start_date' => $role['startdate'], 'team_id' => Team::getIdByField($role['team'], 'name')];
                }
                if (array_key_exists($member['member_id'], $current)) {
                    $m = $current[$member['member_id']];
                } else {
                    $m = new Member();
                }
                $m->fill($member);
                $m->save();
                $m->restore();//verwijderde leden weer herstellen

                if ($m->id > 0) {
                    $m->Teams()->sync($teamids);
                    $m->MemberRoles()->sync($roleids);
                    $m->Seasons()->sync([$current_season_id], false);
                }
            }
            //var_dump($remove_members_not_present);
            if ($remove_members_not_present) {
                //remove all members not in import list
                foreach ($current as $member) {
                    if (!array_key_exists($member->member_id, $members)) {
                        $member->Seasons()->detach([$current_season_id],true);
                    }
                }
            }
        }
    }

    static function clubloten($file)
    {
        $xml = static::getFileContent($file);
        $xml = simplexml_load_string($xml);
        $arr = static::convertToClublotenList($xml);
        $counter = static::countClublotenList($arr);
        $result = static::saveCountedClubloten($counter);
        return $result;
    }

    static function convertToClublotenList($xml)
    {
        if ($xml == false || empty($xml)) {
            throw new \Exception("Bestand is geen geldig XML bestand.");
        }
        if (!isset($xml->Worksheet) || !isset($xml->Worksheet->Table) || !isset($xml->Worksheet->Table->Row)) {
            throw new \Exception("Bestand heeft een onbekende indeling.");
        }
        $records = $xml->Worksheet->Table->Row;
        if (empty($records) || $records->count() <= 1) {
            throw new \Exception("Bestand bevat geen regels.");
        }
        $kopjes = [];
        $rowdata = [];
        $data = [];
        foreach ($records as $cells) {
            /* @var $cells SimpleXMLElement */
            if (empty($kopjes)) {
                foreach ($cells->Cell as $cell) {
                    $attributes = $cell->attributes('ss', true);
                    $kopjes[(int)$attributes->Index] = (string)$cell->Data;
                    $rowdata[(string)$cell->Data] = null;
                }
            } else {
                $record = $rowdata;
                foreach ($cells->Cell as $cell) {
                    $attributes = $cell->attributes('ss', true);
                    $record[$kopjes[(int)$attributes->Index]] = (string)$cell->Data;
                }
                $data[] = $record;
            }

        }
        return $data;
    }

    static function countClublotenList($arr)
    {
        $counted = [];
        foreach ($arr as $record) {
            $record['Verkoper'] = strtolower($record['Verkoper']);
            if (array_key_exists($record['Verkoper'], $counted)) {
                $counted[$record['Verkoper']] += (int)$record['Loten'];
            } else {
                $counted[$record['Verkoper']] = (int)$record['Loten'];
            }
        }
        ksort($counted);
        return $counted;
    }

    static function saveCountedClubloten($counted)
    {
        $season_id = Season::current()->id;
        $members = Member::all(['id', 'member_lastname', 'member_insertion', 'member_firstname']);
        $member_season_data = MemberSeasonData::where('season_id', $season_id)->get();
        $member_season_data = Custom::setKey($member_season_data, 'member_id');
        //print_r($member_season_data);

        $memberlist = [];
        foreach ($members as $member) {
            $memberlist[strtolower($member->fullName())] = $member;
        }
        $errors = [];
        foreach ($counted as $verkoper => $count) {
            if (array_key_exists($verkoper, $memberlist)) {
                $member = $memberlist[$verkoper];
                if (array_key_exists($member->id, $member_season_data)) {
                    $sd = $member_season_data[$member->id];
                } else {
                    $sd = new MemberSeasonData();
                    $sd->season_id = $season_id;
                    $sd->member_id = $member->id;
                }
                $sd->clubloten_count = $count;
                $sd->save();
            } else {
                $errors[] = $verkoper . ' (' . $count . ' loten)';
            }
        }
        return $errors;
    }
}