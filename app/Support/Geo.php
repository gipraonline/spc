<?php

namespace App\Support;

/**
 * Small geo helpers shared by the sales order form, coverage map, field log
 * and incentive/performance reports. Pure functions - no framework needed.
 */
class Geo
{
    private const EARTH_RADIUS_KM = 6371.0088;

    /** Great-circle distance in kilometres (Haversine). */
    public static function distanceKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $rad = M_PI / 180;
        $dLat = ($lat2 - $lat1) * $rad;
        $dLng = ($lng2 - $lng1) * $rad;

        $a = sin($dLat / 2) ** 2
            + cos($lat1 * $rad) * cos($lat2 * $rad) * sin($dLng / 2) ** 2;

        return 2 * self::EARTH_RADIUS_KM * asin(min(1.0, sqrt($a)));
    }

    /** True when both values are usable WGS84 coordinates. */
    public static function valid(mixed $lat, mixed $lng): bool
    {
        return is_numeric($lat) && is_numeric($lng)
            && abs((float) $lat) <= 90 && abs((float) $lng) <= 180
            && ! ((float) $lat === 0.0 && (float) $lng === 0.0);
    }

    /**
     * Rank points by distance from an origin.
     *
     * @param  iterable<array|object>  $points  each needs latitude + longitude
     * @return array<int, array{item: mixed, km: float}>  nearest first
     */
    public static function rank(float $lat, float $lng, iterable $points, ?float $maxKm = null, ?int $limit = null): array
    {
        $out = [];

        foreach ($points as $p) {
            $pLat = is_array($p) ? ($p['latitude'] ?? null) : ($p->latitude ?? null);
            $pLng = is_array($p) ? ($p['longitude'] ?? null) : ($p->longitude ?? null);

            if (! self::valid($pLat, $pLng)) {
                continue;
            }

            $km = self::distanceKm($lat, $lng, (float) $pLat, (float) $pLng);

            if ($maxKm !== null && $km > $maxKm) {
                continue;
            }

            $out[] = ['item' => $p, 'km' => round($km, 2)];
        }

        usort($out, fn ($a, $b) => $a['km'] <=> $b['km']);

        return $limit ? array_slice($out, 0, $limit) : $out;
    }

    /** Google Maps link for a coordinate pair (no API key needed). */
    public static function mapsUrl(mixed $lat, mixed $lng): ?string
    {
        return self::valid($lat, $lng)
            ? 'https://www.google.com/maps?q='.((float) $lat).','.((float) $lng)
            : null;
    }
}
