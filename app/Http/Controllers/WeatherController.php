<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class WeatherController extends BaseController
{
    private $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function getCurrentWeather(Request $request): JsonResponse
    {
        $this->validate($request, [
            'location' => 'required|string',
        ], [
            'location.required' => 'The "location" parameter is required.',
            'location.string' => 'The "location" parameter must be a string.',
        ]);

        $location = $request->input('location');

        try {
            $weather = $this->weatherService->getCurrentWeather($location);
            return response()->json($weather);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getForecast(Request $request): JsonResponse
    {
        $this->validate($request, [
            'location' => 'required|string',
        ], [
            'location.required' => 'The "location" parameter is required.',
            'location.string' => 'The "location" parameter must be a string.',
        ]);

        $location = $request->input('location');

        try {
            $forecast = $this->weatherService->getForecast($location);
            return response()->json($forecast);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getHistory(Request $request): JsonResponse
    {
        $this->validate($request, [
            'location' => 'required|string',
            'date' => 'required|date_format:Y-m-d',
        ], [
            'location.required' => 'The "location" parameter is required.',
            'location.string' => 'The "location" parameter must be a string.',
            'date.required' => 'The "date" parameter is required.',
            'date.date_format' => 'The "date" parameter must be in YYYY-MM-DD format.',
        ]);

        $location = $request->input('location');
        $date = $request->input('date');

        try {
            $history = $this->weatherService->getHistory($location, $date);
            return response()->json($history);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
