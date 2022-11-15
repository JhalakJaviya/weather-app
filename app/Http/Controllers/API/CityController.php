<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiController;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\StoreCityRequest;
use App\Http\Resources\CityResource;
use App\Http\Services\OpenWeatherMapAPI;

class CityController extends ApiController
{
    private OpenWeatherMapAPI $openWeatherMapAPI;

    public function __construct(OpenWeatherMapAPI $openWeatherMapAPI)
    {
        $this->openWeatherMapAPI = $openWeatherMapAPI;
    }

    /**
     * Return a listing of the cities.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $cities = City::all();

            $cities->each(function ($collection) {
                $collection->weather_data = $this->openWeatherMapAPI->getForecastWeatherData($collection);
            });

            return $this->successResponse(CityResource::collection($cities), __('api_responses.cities.index.success'));
        } catch (\Exception $error) {
            dd($error);
            return $this->errorResponse(__('api_responses.cities.index.error'), 500);
        }
    }

    /**
     * Store a newly created city in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCityRequest $request, City $city)
    {
        try {
            $weatherData = $this->openWeatherMapAPI->getCurrentWeatherData($request->name);

            $newCity = $city->firstOrCreate([
                'name' => $weatherData['name'],
            ], [
                    'lon' => $weatherData['coord']['lon'],
                    'lat' => $weatherData['coord']['lat']
                ]);

            $newCity->weather_data = $weatherData;

            return $this->successResponse(new CityResource($newCity), __('api_responses.cities.store.success'), 201);
        } catch (ModelNotFoundException $error) {
            return $this->errorResponse($error->getMessage(), 404);
        } catch (\Exception $error) {
            return $this->errorResponse(__('api_responses.cities.store.error'), 500);
        }
    }

    /**
     * Display the specified city.
     *
     * @param  $cityId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($cityId)
    {
        $city = City::find($cityId);

        if (!is_null($city)) {
            $city->weather_data = $this->openWeatherMapAPI->getForecastWeatherData($city);
            return $this->successResponse(new CityResource($city), __('api_responses.cities.show.success'));
        }

        return $this->errorResponse(__('api_responses.cities.show.error'), 404);
    }

    /**
     * Update the specified city in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, City $city)
    {
        //
    }

    /**
     * Remove the specified city from storage.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(City $city)
    {
        //
    }
}