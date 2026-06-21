<?php

namespace Tests\Unit\Services\Zoom;

use App\Services\Zoom\ZoomOAuthClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ZoomOAuthClientTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::forget('zoom.oauth.token');
        config()->set('services.zoom', [
            'client_id' => 'cid',
            'client_secret' => 'csec',
            'account_id' => 'aid',
            'api_url' => 'https://api.zoom.us/v2/',
        ]);
    }

    /** @test */
    public function token_request_uses_basic_auth_and_account_credentials_grant()
    {
        Http::fake([
            'zoom.us/oauth/token' => Http::response(['access_token' => 'tok-123'], 200),
        ]);

        Http::fake([
            'api.zoom.us/v2/users/me*' => Http::response(['id' => 'me'], 200),
        ]);

        $client = new ZoomOAuthClient;
        $client->get('users/me');

        Http::assertSent(function ($request) {
            return $request->url() === 'https://zoom.us/oauth/token'
                && $request->method() === 'POST'
                && $request->header('Authorization')[0] === 'Basic ' . base64_encode('cid:csec')
                && $request['grant_type'] === 'account_credentials'
                && $request['account_id'] === 'aid';
        });
    }

    /** @test */
    public function token_is_cached_between_requests()
    {
        Http::fake([
            'zoom.us/oauth/token' => Http::response(['access_token' => 'tok-cached'], 200),
        ]);
        Http::fake([
            'api.zoom.us/v2/ping*' => Http::sequence()
                ->push(['ok' => 1], 200)
                ->push(['ok' => 2], 200),
        ]);

        $client = new ZoomOAuthClient;
        $client->get('ping');
        $client->get('ping');

        Http::assertSentCount(3);
    }

    /** @test */
    public function subsequent_requests_carry_bearer_token()
    {
        Http::fake([
            'zoom.us/oauth/token' => Http::response(['access_token' => 'bearer-tok'], 200),
        ]);
        Http::fake([
            'api.zoom.us/v2/whoami*' => Http::response(['user' => 'me'], 200),
        ]);

        (new ZoomOAuthClient)->get('whoami');

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.zoom.us/v2/whoami'
                && $request->header('Authorization')[0] === 'Bearer bearer-tok';
        });
    }

    /** @test */
    public function get_passes_query_string()
    {
        Http::fake([
            'zoom.us/oauth/token' => Http::response(['access_token' => 't'], 200),
            'api.zoom.us/v2/list*' => Http::response([], 200),
        ]);

        (new ZoomOAuthClient)->get('list', ['page' => 2, 'size' => 10]);

        Http::assertSent(function ($request) {
            return $request->method() === 'GET'
                && $request->url() === 'https://api.zoom.us/v2/list?page=2&size=10';
        });
    }

    /** @test */
    public function post_serializes_body_as_json()
    {
        Http::fake([
            'zoom.us/oauth/token' => Http::response(['access_token' => 't'], 200),
            'api.zoom.us/v2/thing' => Http::response(['ok' => true], 200),
        ]);

        (new ZoomOAuthClient)->post('thing', ['a' => 1, 'b' => 2]);

        Http::assertSent(function ($request) {
            return $request->method() === 'POST'
                && $request->url() === 'https://api.zoom.us/v2/thing'
                && $request->data() === ['a' => 1, 'b' => 2];
        });
    }

    /** @test */
    public function toZoomTimeFormat_returns_iso_format()
    {
        $client = new ZoomOAuthClient;

        $this->assertSame('2024-01-15T09:30:00', $client->toZoomTimeFormat('2024-01-15 09:30:00'));
    }

    /** @test */
    public function toZoomTimeFormat_returns_empty_string_on_invalid_input()
    {
        $client = new ZoomOAuthClient;

        $this->assertSame('', $client->toZoomTimeFormat('not-a-date'));
    }

    /** @test */
    public function toUnixTimeStamp_respects_timezone()
    {
        $client = new ZoomOAuthClient;

        $timestamp = $client->toUnixTimeStamp('2024-01-15 04:30:00', 'UTC');

        $this->assertSame((string) (new \DateTime('2024-01-15 04:30:00', new \DateTimeZone('UTC')))->getTimestamp(), $timestamp);
    }
}
