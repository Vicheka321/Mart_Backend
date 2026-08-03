<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class GoogleRouteService
{
    protected string $apiKey;

    protected string $url = 'https://routes.googleapis.com/directions/v2:computeRoutes';

    public function __construct()
    {
        $this->apiKey = config('services.google.api_key');
    }

    /**
     * Get driving distance in KM
     */
    public function getDrivingDistance(
        float $originLat,
        float $originLng,
        float $destLat,
        float $destLng
    ): float {

        try {

            $response = Http::timeout(15)
                ->withHeaders([
                    'X-Goog-Api-Key' => $this->apiKey,
                    'X-Goog-FieldMask' => 'routes.distanceMeters,routes.duration',
                ])
                ->post($this->url, [

                    'origin' => [
                        'location' => [
                            'latLng' => [
                                'latitude' => $originLat,
                                'longitude' => $originLng,
                            ],
                        ],
                    ],

                    'destination' => [
                        'location' => [
                            'latLng' => [
                                'latitude' => $destLat,
                                'longitude' => $destLng,
                            ],
                        ],
                    ],

                    'travelMode' => 'DRIVE',

                    'routingPreference' => 'TRAFFIC_UNAWARE',

                    'computeAlternativeRoutes' => false,

                    'languageCode' => 'en-US',

                    'units' => 'METRIC',

                ]);

            if (!$response->successful()) {

                Log::error('Google Routes API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new Exception('Unable to calculate route.');
            }

            $data = $response->json();

            if (!isset($data['routes'][0]['distanceMeters'])) {

                throw new Exception('Distance not found.');
            }


            return round(
                $data['routes'][0]['distanceMeters'] / 1000,
                2
            );
        } catch (Exception $e) {

            Log::error('GoogleRouteService', [
                'message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
