<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\PendingRequest;
use Throwable;

class ApiClient
{
    /**
     * The pre-configured HTTP client instance.
     */
    protected PendingRequest $client;

    public function __construct()
    {
        $baseUrl = rtrim(config('services.api.url'), '/');

        $this->client = Http::baseUrl($baseUrl)
                            ->acceptJson() 
                            ->contentType('application/json'); 
    }

    /**
     * Get the client, adding the auth token if it exists in the session.
     */
    protected function getClient(): PendingRequest
    {
        $client = clone $this->client; 

        if ($token = session('api_token')) {
            return $client->withToken($token);
        }
        return $client;
    }

    /**
     * Handle successful responses and errors consistently.
     */
    private function handleResponse(Response $response): array
    {
        if ($response->successful()) {
            $data = $response->json();
            $data['ok'] = true; 
            return $data;
        }

        // Return a structured error
        return [
            'ok' => false,
            'error' => $response->json('message') ?? $response->json('error') ?? 'API Error (' . $response->status() . ')'
        ];
    }

    /**
     * Handle fatal connection errors.
     */
    private function handleError(Throwable $e): array
    {
        return [
            'ok' => false,
            'error' => 'API Connection Error', 
        ];
    }

    /**
     * Make a POST request.
     */
    public function post($endpoint, $data = [])
    {
        try {
            // Use the base client (no token) for public actions like login/register
            $response = $this->client->post($endpoint, $data);
            return $this->handleResponse($response);

        } catch (Throwable $e) {
            return $this->handleError($e);
        }
    }

    /**
     * Make a GET request.
     */
    public function get($endpoint)
    {
        try {
            $response = $this->getClient()->get($endpoint);
            return $this->handleResponse($response);

        } catch (Throwable $e) {
            return $this->handleError($e);
        }
    }

    /**
     * Make a PUT/PATCH request.
     */
    public function put($endpoint, $data = [])
    {
        try {
            $response = $this->getClient()->put($endpoint, $data);
            return $this->handleResponse($response);
        } catch (Throwable $e) {
            return $this->handleError($e);
        }
    }

    /**
     * Make a DELETE request.
     */
    public function delete($endpoint)
    {
        try {
            $response = $this->getClient()->delete($endpoint);
            return $this->handleResponse($response);
        } catch (Throwable $e) {
            return $this->handleError($e);
        }
    }

    public function authedPost($endpoint, $data = [])
    {
        try {
            // Get the client with the token
            $response = $this->getClient()->post($endpoint, $data);
            return $this->handleResponse($response);

        } catch (Throwable $e) {
            return $this->handleError($e);
        }
    }
}