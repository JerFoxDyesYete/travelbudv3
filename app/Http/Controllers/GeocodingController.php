<?php

namespace App\Http\Controllers;

use App\Services\GeocodingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GeocodingController extends Controller
{
    private $geocodingService;

    public function __construct(GeocodingService $geocodingService)
    {
        $this->geocodingService = $geocodingService;
    }

    public function getByLatLng(Request $request): JsonResponse
    {
        $lat = $request->query('lat');
        $lng = $request->query('lng');

        if (!$lat || !$lng) {
            return response()->json(['error' => 'lat and lng parameter are required'], 400);
        }

        try {
            $data = $this->geocodingService->getByLatLng($lat, $lng);
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getByPlaceId(Request $request): JsonResponse
    {
        $placeId = $request->query('placeid');

        if (!$placeId) {
            return response()->json(['error' => 'placeid parameter is required'], 400);
        }

        try {
            $data = $this->geocodingService->getByPlaceId($placeId);
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getByAddress(Request $request): JsonResponse
    {
        $address = $request->query('address');

        if (!$address) {
            return response()->json(['error' => 'address parameter is required'], 400);
        }

        try {
            $data = $this->geocodingService->getByAddress($address);
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
