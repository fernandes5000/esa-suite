<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiClient
{
    public function post($endpoint, $data = [])
    {
        $url = rtrim(config('services.api.url'), '/') . $endpoint;

        try {
            $response = Http::post($url, $data);

            if ($response->successful()) {
                $data = $response->json();
                $data['ok'] = true;
                return $data;
            }

            return [
                'ok' => false,
                'error' => $response->json()['message'] ?? 'Unknown API error'
            ];

        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function get($endpoint)
    {
        $url = rtrim(config('services.api.url'), '/') . $endpoint;

        try {
            $response = Http::withToken(session('api_token'))->get($url);

            if ($response->successful()) {
                $data = $response->json();
                $data['ok'] = true;
                return $data;
            }

            return [
                'ok' => false,
                'error' => $response->json()['message'] ?? 'Unknown API error',
            ];

        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}