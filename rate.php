<?php

function get_json($url)
{
    $response = '';
    if ( function_exists( 'curl_version' ) ) {
        $curl = curl_init( $url );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
        $response = curl_exec( $curl );
        curl_close( $curl );
    } elseif ( ini_get( 'allow_url_fopen' ) ) {
        $response = file_get_contents( $url );
    }
    return json_decode( $response );
}

function get_ltc_usd_from_cryptonator()
{
    $currency_from = "ltc";
    $currency_to = "usd";
    $url = "https://api.cryptonator.com/api/ticker/" . $currency_from. "-" . $currency_to;
    $exchange_rate = get_json($url);
    $rate = ( isset( $exchange_rate->ticker->price ) ) ? floatval( $exchange_rate->ticker->price ) : 0.0;
    return $rate;
}

function get_ltc_usd_rate_from_litecoin_com()
{
    $url = "https://litecoin.com/api/v1/ticker";
    $exchange_rate = get_json($url)->price;
    $rate = 0;
    foreach ($exchange_rate as $value) {
        if ($value->code == "USD")
        $rate = $value->n;
    }
    return $rate;
}

function get_ltc_usd_rate()
{
    $rate = get_ltc_usd_from_cryptonator();
    if($rate != 0.0)
        return $rate;

    $rate = get_ltc_usd_rate_from_litecoin_com();
    if($rate != 0.0)
        return $rate;
}

?>
