<?php

namespace App\Http\Services;

use App\Models\City;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OpenWeatherMapAPI
{
    private $apiKey;
    private $weatherDataUnits;
    private $currentWeatherDataAPI;
    private $forecast5WeatherDataAPI;

    public function __construct()
    {
        $this->apiKey = Config::get('services.openweathermap.key');
        $this->weatherDataUnits = Config::get('constants.openweathermap.units');
        $this->currentWeatherDataAPI = Config::get('constants.openweathermap.apis.weather_data.current');
        $this->forecast5WeatherDataAPI = Config::get('constants.openweathermap.apis.weather_data.forecast5');
    }

    public function getCurrentWeatherData($city)
    {
        $weatherDataResponse = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->get($this->currentWeatherDataAPI, [
                'q' => $city,
                'units' => $this->weatherDataUnits,
                'appid' => $this->apiKey
            ]);

        if ($weatherDataResponse->getStatusCode() === 404) {
            throw new ModelNotFoundException(__('api_responses.cities.index.model_not_found_error'));
        }

        return json_decode((string) $weatherDataResponse->getBody(), true);
    }

    public function getForecastWeatherData($city)
    {
        $weatherDataResponse = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->get($this->forecast5WeatherDataAPI, [
                'units' => $this->weatherDataUnits,
                'lon' => $city->lon,
                'lat' => $city->lat,
                'appid' => $this->apiKey
            ]);

        return json_decode((string) $weatherDataResponse->getBody(), true);
    }
}