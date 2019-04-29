<?php

namespace App;
class Custom{
    static function String($string){
        return new CustomString($string);
    }
    static function setKey($models,$key){
        $result = [];
        foreach($models as $model){
            $result[$model->$key] = $model;
        }
        return $result;
    }
}
class CustomString{
    protected $string;
    function __construct($string) {
        $this->string = $string;
    }
    function getDateTime(){ 
        $result = $this->string;
        @list($date,$time) = explode(" ",$result); 
        if(strstr($date,'-')){
            $parts = explode("-",$date);
        }
        if($parts[0]>100 && $parts[1]<=12 && $parts[2]<=32){
            //YYYY-mm-dd notatie
        }elseif($parts[0]<=31 && $parts[1]<=12 && $parts[2]>100){
            //dd-mm-YYYY notatie
            $result = $parts[2].'-'.$parts[1].'-'.$parts[0];
            if($time!=''){
                $result.=" ".$time;
            }
        }
        return new \DateTime($result);
    }
    function getGender(){
        $gender = strtolower(trim($this->string));
        if(in_array($gender,array("m","men","man","male","meneer","heer"))){
            return "m";
        }
        if(in_array($gender,array("f","lady","female","vrouw","mevrouw","lady","girl"))){
            return "f";
        }
        return "";
    }
}
