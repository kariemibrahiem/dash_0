<?php
namespace App\Traits;

use GuzzleHttp\Client;
use Stevebauman\Location\Facades\Location; // 📌 استدعاء الباكدج اللي انت مركبه

Trait WeatherTrait
{
    public function GetWeatherClient(){
        return new Client([
            "base_uri" => "https://api.openweathermap.org/data/2.5/",
            "timeout"  => 5.0,
        ]);
    }

    public function GetWeather($lat, $lon){
        $client = $this->GetWeatherClient();

        // 📌 أولاً: نحاول نجيب الـ location من الباكدج
        $location = Location::get(request()->ip());

        if ($location && $location->latitude && $location->longitude) {
            // 📌 لو الباكدج رجع إحداثيات → نستخدمها
            $lat = $location->latitude;
            $lon = $location->longitude;
        } else {
            // 📌 لو الباكدج مرجعش حاجة → نستخدم الإحداثيات اللي جايه من الـ params
            $lat = $lat;
            $lon = $lon;
        }

        // 📌 هنا نعمل request للـ API
        $response = $client->get("weather" , [
            "query" => [
                "lat" => $lat,
                "lon" => $lon,
                "appid" => env("WEATHER_API_KEY"), // مفتاحك من .env
                "units" => "metric"
            ]
        ]);

        // 📌 نقرأ البيانات من response ونرجعها بشكل مختصر
        $data = json_decode($response->getBody()->getContents() , true);
        $data = [
            "temp"      => $data['main']["temp"],
            "pressure"  => $data['main']["pressure"],
            "humidity"  => $data['main']["humidity"],
            "wind_speed"=> $data['wind']["speed"]
        ];
        return $data;
    }
}
