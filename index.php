<?php

use Ftembe\Cachehandler\Cachehandler;

require 'vendor/autoload.php';

function fetchDataFromApi($endPoint =  'https://jsonplaceholder.typicode.com/photos/')
{

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_URL, $endPoint);
    curl_setopt($curl,  CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);

    if ($e = curl_error($curl))
        echo $e;

    curl_close($curl);

    return $response;
}

$cacheHandler = new Cachehandler('cache');

if ($cacheHandler->getDataLength('photos') != 5000){
    $store = $cacheHandler->store('photos', json_decode(fetchDataFromApi()), '1 hour');
}

$getDataLength = $cacheHandler->getDataLength('photos');
$getDataType = $cacheHandler->getDataType('photos');

$get = $cacheHandler->get('photos');


var_dump($get);
var_dump($getDataLength);
var_dump($getDataType);
