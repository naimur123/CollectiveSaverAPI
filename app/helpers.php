<?php

use Illuminate\Support\Facades\Http;

  if(!function_exists('user_location()')){
       function user_location($ip = ''){
          $response = Http::get("https://ipinfo.io/{$ip}/geo");
          if ($response->successful()) {
            $locationData = $response->json();
            $address = $locationData['loc'] ?? 'Unknown';
            $city = $locationData['city'] ?? 'Unknown';
            $region = $locationData['region'] ?? 'Unknown';
            $country = $locationData['country'] ?? 'Unknown';

            $location = $address . ',' . $city .','. $region . ','. $country;
            return $location;
          }else{

            $location = 'Unknown';
            return $location;

          }
       }
  }

  if(!function_exists('getModelInstance()')){
    function getModelInstance($model)
    {
        $modelClass = "App\\Models\\" . $model;
        if (class_exists($modelClass)) {
            return new $modelClass;
        }
    }
  }
