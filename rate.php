<?php

function get_json($url)
{
    $response = '';
    if ( function_exists( 'curl_version' ) && function_exists( 'curl_init' ) ) {
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

function get_ltc_usd_rate_from_bitstamp_net()
{
    $currency_to = "usd";
    $url = "https://www.bitstamp.net/api/v2/ticker/ltc". $currency_to;
    $prices = get_json($url);
    $rate = ( isset( $prices->last ) ) ? floatval( $prices->last ) : 0.0;
    return $rate;
}

function get_ltc_usd_rate_from_gdax()
{
    $currency_to = "usd";
    $url = "https://api.coinbase.com/v2/prices/ltc-" . $currency_to . "/spot";
    $prices = get_json($url)->data;
    $rate = ( isset( $prices->amount ) ) ? floatval( $prices->amount ) : 0.0;
    return $rate;
}

function get_ltc_usd_rate_from_fiat2ltc_com()
{
    $currency_to = "usd";
    $url = "https://fiat2ltc.com/API/" . $currency_to;
    $prices = get_json($url);
    $rate = ( isset( $prices->index ) ) ? floatval( $prices->index ) : 0.0;
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

    $rate = get_ltc_usd_rate_from_gdax();
    if($rate != 0.0)
        return $rate;

    $rate = get_ltc_usd_rate_from_bitstamp_net();
    if($rate != 0.0)
        return $rate;

    $rate = get_ltc_usd_rate_from_fiat2ltc_com();
    if($rate != 0.0)
        return $rate;
}

?>
