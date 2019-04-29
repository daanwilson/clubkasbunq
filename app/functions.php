<?php

function NumberFormat($number, $decimals = 2, $dec_point = ",", $thousands_sep = ".") {
    return number_format($number, $decimals, $dec_point, $thousands_sep);
}

function MoneyFormat($amount, $currency = "EUR") {
    $currencys = [
        "EUR" => "&euro;",
        "USD" => "&dollar;",
        "GBP" => "&pound;",
        "JPY" => "&yen;",
    ];
    return $currencys[$currency] . "&nbsp;" . NumberFormat($amount);
}

function DateFormat($date, $format = "d-m-Y") {
    return DateTimeFormat($date, 'd-m-Y');
}

function DateTimeFormat($date, $format = "d-m-Y H:i") {
    return (new \Carbon\Carbon($date))->format($format);
}

function Setting($key) {
    $settings = \App\Setting::allCachedByKey('key');
    if (array_key_exists($key, $settings)) {
        return $settings[$key]->value;
    }
}

function getChunked($string,$chunk_length, $chunk_space = " ") {
    $string = str_replace($chunk_space, "", $string);
    return substr(chunk_split($string, $chunk_length, $chunk_space), 0, -1 * strlen($chunk_space));
}
function MakeAmount($string){
    $amount = (double)str_replace(",",".",$string);
    return $amount;
}
function print_pre($arg){
    if(is_array($arg)){
        echo '<pre>'.print_r($arg).'</pre>';
    }else{
        var_dump($arg);
    }
}