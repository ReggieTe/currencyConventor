<?php

class Currency {

    function __construct() {
        
    }
    public static function currentDisplay() {
    $currencySymbols = array('dollar' => 'US-Dollar', 'rand' => 'South African Rand', 'kwacha' => 'Zambian Kwacha', 'pula' => 'Botswana Pula', 'bitcoin' => 'BitCoin');
    if (Session::get('currency') != null) {
        $currencySign = Session::get('currency');

        $symbol = ucfirst($currencySymbols[strtolower($currencySign)]);

        return $symbol;
    } else {
        return $currencySymbols['dollar'];
    }
}

public static function getPriceConversion($priceInUSDollar=0) {
    $currencySymbols = array('dollar' => '$', 'rand' => 'R', 'kwacha' => 'K', 'pula' => 'P', 'bitcoin' => 'B');
    if (Session::get('currency') != null) {
        $currencySign = Session::get('currency');
        $currencyConversionRate = Session::get('currency-value');
        $newPrice = round($priceInUSDollar * $currencyConversionRate, 5);
        $symbol = $currencySymbols[strtolower($currencySign)];
        return $symbol . $newPrice;
    } else {
        return $currencySymbols['dollar'] . $priceInUSDollar;
    }
}
public static function Conventer( $to_Currency,$from_Currency='USD', $amount=1) {
    $amount = urlencode($amount);
    $from_Currency = urlencode($from_Currency);
    $to_Currency = urlencode($to_Currency);

    $url = "http://www.google.com/finance/converter?a=$amount&from=$from_Currency&to=$to_Currency";

    $ch = curl_init();
    $timeout = 0;
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt ($ch, CURLOPT_USERAGENT,
                 "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $rawdata = curl_exec($ch);
    curl_close($ch);
    $data = explode('bld>', $rawdata);
    @$data = explode($to_Currency, $data[1]);

    return round($data[0], 2);
}


public static function bitcoinConventer($amount=1,$currency="USD")
{
    $url="https://blockchain.info/tobtc?currency=$currency&value=$amount";
    @$value=file_get_contents($url);
    
    return $value;
}



}




