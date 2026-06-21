<?php

namespace App\Services\Zoom;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZoomOAuthClient
{
    public function get(string $path, array $query = []): Response
    {
        return $this->request()->get($this->apiUrl() . $path, $query);
    }

    public function post(string $path, array $body = []): Response
    {
        return $this->request()->post($this->apiUrl() . $path, $body);
    }

    public function put(string $path, array $body = []): Response
    {
        return $this->request()->put($this->apiUrl() . $path, $body);
    }

    public function patch(string $path, array $body = []): Response
    {
        return $this->request()->patch($this->apiUrl() . $path, $body);
    }

    public function delete(string $path, array $body = []): Response
    {
        return $this->request()->delete($this->apiUrl() . $path, $body);
    }

    public function toZoomTimeFormat(string $dateTime): string
    {
        try {
            return (new \DateTime($dateTime))->format('Y-m-d\TH:i:s');
        } catch (\Exception $e) {
            Log::error('ZoomOAuthClient->toZoomTimeFormat: ' . $e->getMessage());

            return '';
        }
    }

    public function toUnixTimeStamp(string $dateTime, string $timezone): string
    {
        try {
            $date = new \DateTime($dateTime, new \DateTimeZone($timezone));

            return (string) $date->getTimestamp();
        } catch (\Exception $e) {
            Log::error('ZoomOAuthClient->toUnixTimeStamp: ' . $e->getMessage());

            return '';
        }
    }

    private function request(): PendingRequest
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token(),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
    }

    private function token(): string
    {
        return Cache::remember('zoom.oauth.token', 3300, function () {
            $clientId = config('services.zoom.client_id', env('ZOOM_CLIENT_ID', ''));
            $clientSecret = config('services.zoom.client_secret', env('ZOOM_CLIENT_SECRET', ''));
            $accountId = config('services.zoom.account_id', env('ZOOM_ACCOUNT_ID', ''));

            $response = Http::asForm()->withBasicAuth($clientId, $clientSecret)
                ->post('https://zoom.us/oauth/token', [
                    'grant_type' => 'account_credentials',
                    'account_id' => $accountId,
                ]);

            if ($response->failed()) {
                Log::error('ZoomOAuthClient: No se pudo obtener token OAuth.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }

            return $response->json('access_token', '');
        });
    }

    private function apiUrl(): string
    {
        return config('services.zoom.api_url', env('ZOOM_API_URL', ''));
    }
}
