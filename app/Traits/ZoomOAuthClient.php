<?php

namespace App\Traits;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait ZoomOAuthClient
{
    private function generateOAuthToken(): string
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

    private function retrieveZoomUrl(): string
    {
        return config('services.zoom.api_url', env('ZOOM_API_URL', ''));
    }

    private function zoomOAuthRequest(): \Illuminate\Http\Client\PendingRequest
    {
        $token = $this->generateOAuthToken();

        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
    }

    public function zoomGet(string $path, array $query = []): Response
    {
        $url = $this->retrieveZoomUrl();

        return $this->zoomOAuthRequest()->get($url . $path, $query);
    }

    public function zoomPost(string $path, array $body = []): Response
    {
        $url = $this->retrieveZoomUrl();

        return $this->zoomOAuthRequest()->post($url . $path, $body);
    }

    public function zoomPut(string $path, array $body = []): Response
    {
        $url = $this->retrieveZoomUrl();

        return $this->zoomOAuthRequest()->put($url . $path, $body);
    }

    public function zoomPatch(string $path, array $body = []): Response
    {
        $url = $this->retrieveZoomUrl();

        return $this->zoomOAuthRequest()->patch($url . $path, $body);
    }

    public function zoomDelete(string $path, array $body = []): Response
    {
        $url = $this->retrieveZoomUrl();

        return $this->zoomOAuthRequest()->delete($url . $path, $body);
    }

    public function toZoomTimeFormat(string $dateTime): string
    {
        try {
            $date = new \DateTime($dateTime);

            return $date->format('Y-m-d\TH:i:s');
        } catch (\Exception $e) {
            Log::error('ZoomOAuthClient->toZoomTimeFormat : ' . $e->getMessage());

            return '';
        }
    }

    public function toUnixTimeStamp(string $dateTime, string $timezone): string
    {
        try {
            $date = new \DateTime($dateTime, new \DateTimeZone($timezone));

            return (string) $date->getTimestamp();
        } catch (\Exception $e) {
            Log::error('ZoomOAuthClient->toUnixTimeStamp : ' . $e->getMessage());

            return '';
        }
    }
}
