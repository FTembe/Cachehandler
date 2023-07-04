
## Cache handler 

The project is a lightweight caching solution that improves performance by storing and retrieving data efficiently. It offers a file-based storage approach with customizable expiration times, automatic removal of expired data, and ensures the cache folder is accessible and writable. Integrating this caching mechanism enhances application speed and reduces processing time.

## Installation 

```bash 
composer require ftembe/cachehandler
```
Or 
```bash
git clone https://github.com/FTembe/Cachehandler.git
```

 ## How to use
 ```
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

/*
You can create a folder to receive temporary data and pass it as a parameter when instantiating the CacheHandler class
eg. $cacheHandler = new Cachehandler('cacheFolder');
*/

$cacheHandler = new Cachehandler();

if ($cacheHandler->getDataLength('photos') != 5000){
    $store = $cacheHandler->store('photos', json_decode(fetchDataFromApi()), '1 hour');
}

$getDataLength = $cacheHandler->getDataLength('photos');
$getDataType = $cacheHandler->getDataType('photos');

$get = $cacheHandler->get('photos');


var_dump($get);
var_dump($getDataLength);
var_dump($getDataType);
```
