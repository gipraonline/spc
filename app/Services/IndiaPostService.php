<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class IndiaPostService
{
    private const TOKEN_CACHE_KEY = 'indiapost.access_token';
    private const MAX_PER_REQUEST = 500;

    /**
     * Login and cache the bearer token (India Post tokens live ~15 minutes).
     */
    public function getAccessToken(bool $forceRefresh = false): string
    {
        if ($forceRefresh) {
            Cache::forget(self::TOKEN_CACHE_KEY);
        }

        $cached = Cache::get(self::TOKEN_CACHE_KEY);
        if ($cached) {
            return $cached;
        }

        $response = Http::baseUrl(config('indiapost.base_url'))
            ->acceptJson()
            ->asJson()
            ->timeout(config('indiapost.timeout'))
            ->post('/v1/access/login', [
                'username' => config('indiapost.username'),
                'password' => config('indiapost.password'),
            ]);

        if ($response->failed() || !$response->json('success')) {
            Log::error('IndiaPost login failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            throw new RuntimeException('India Post login failed.');
        }

        $data  = $response->json('data');
        $token = $data['access_token'] ?? null;

        if (!$token) {
            throw new RuntimeException('India Post login returned no access token.');
        }

        // Cache slightly shorter than the real expiry to avoid using a dead token.
        $ttl = max(60, ((int) ($data['expires_in'] ?? 900)) - 60);
        Cache::put(self::TOKEN_CACHE_KEY, $token, $ttl);

        return $token;
    }

    /**
     * Track one or many article numbers. Returns normalized results.
     *
     * @param  array<int,string> $articleNumbers
     * @return array{found: array<int,array>, not_found: array<int,string>}
     */
    public function track(array $articleNumbers): array
    {
        $numbers = array_values(array_unique(array_map(
            fn ($n) => strtoupper(trim((string) $n)),
            $articleNumbers
        )));

        $found = [];

        foreach (array_chunk($numbers, self::MAX_PER_REQUEST) as $chunk) {
            $response = $this->post('/v1/tracking/bulk', ['bulk' => $chunk]);

            if ($response->failed() || !$response->json('success')) {
                Log::error('IndiaPost tracking failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                throw new RuntimeException('India Post tracking request failed.');
            }

            foreach ((array) $response->json('data', []) as $item) {
                $found[] = $this->normalize($item);
            }
        }

        $returned = array_column($found, 'article_number');
        $notFound = array_values(array_diff($numbers, $returned));

        return [
            'found'     => $found,
            'not_found' => $notFound,
        ];
    }

    private function client(): PendingRequest
    {
        return Http::baseUrl(config('indiapost.base_url'))
            ->acceptJson()
            ->asJson()
            ->timeout(config('indiapost.timeout'))
            ->withToken($this->getAccessToken());
    }

    /**
     * POST with one automatic re-login if the token was rejected.
     */
    private function post(string $path, array $payload): Response
    {
        $response = $this->client()->post($path, $payload);

        if ($response->status() === 401) {
            $this->getAccessToken(true);
            $response = $this->client()->post($path, $payload);
        }

        return $response;
    }

    private function normalize(array $item): array
    {
        $booking = $item['booking_details'] ?? [];
        $events  = $item['tracking_details'] ?? [];

        return [
            'article_number'        => $booking['article_number'] ?? null,
            'article_type'          => $booking['article_type'] ?? null,
            'booked_at'             => $booking['booked_at'] ?? null,
            'booked_on'             => $booking['booked_on'] ?? null,
            'origin_pincode'        => $booking['origin_pincode'] ?? null,
            'destination_pincode'   => $booking['destination_pincode'] ?? null,
            'delivery_location'     => $booking['delivery_location'] ?? null,
            'delivery_confirmed_on' => $booking['delivery_confirmed_on'] ?? null,
            'delivered'             => !empty($booking['delivery_confirmed_on']),
            'status'                => $item['del_status']['del_status'] ?? null,
            'latest_event'          => $events[0]['event'] ?? null,
            'events'                => array_map(fn ($e) => [
                'date'    => $e['date'] ?? null,
                'time'    => $e['time'] ?? null,
                'office'  => $e['office'] ?? null,
                'event'   => $e['event'] ?? null,
                'remarks' => $e['remarks'] ?? null,
                'rts'     => (bool) ($e['rts'] ?? false),
            ], $events),
        ];
    }
}
