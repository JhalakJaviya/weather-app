<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\City;
use Illuminate\Http\Response;

class CityTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCityIsCreatedSuccessfully()
    {
        $data = [
            'name' => 'New Delhi',
        ];

        $this->json('post', route('cities.store'), $data)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                    'lon',
                    'lat',
                    'weather_data',
                ]
            ]);


        $this->assertDatabaseHas('cities', $data);
    }

    public function testCityIsNotCreatedOnInvalidName()
    {
        $data = [
            'name' => 'Test',
        ];

        $this->json('post', route('cities.store'), $data)
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                'status',
                'message',
            ]);


        $this->assertDatabaseMissing('cities', $data);
    }

    public function testGetCitiesApi()
    {
        $city = City::create([
            'name' => $this->faker->city,
            'lon' => $this->faker->longitude,
            'lat' => $this->faker->latitude
        ]);

        $this->json('get', route('cities.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'lon',
                        'lat',
                        'weather_data'
                    ]
                ]
            ]);
    }

    public function testGetCityApi()
    {
        $city = City::create([
            'name' => $this->faker->city,
            'lon' => $this->faker->longitude,
            'lat' => $this->faker->latitude
        ]);

        $this->json('get', route('cities.show', [$city->id]))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                    'lon',
                    'lat',
                    'weather_data'
                ]
            ]);
    }
}