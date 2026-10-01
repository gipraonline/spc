{{-- Where the employee checked in / out. Expects $fieldLog. --}}
@php
    $moved = $fieldLog->distanceMovedKm();
    $maxAcc = (int) config('spc.field_log_max_accuracy_m', 200);
    $row = function ($label, $has, $lat, $lng, $acc, $url) use ($maxAcc) {
        return compact('label', 'has', 'lat', 'lng', 'acc', 'url', 'maxAcc');
    };
    $rows = [
        $row('Check in', $fieldLog->hasCheckInLocation(), $fieldLog->check_in_latitude, $fieldLog->check_in_longitude, $fieldLog->check_in_accuracy_m, $fieldLog->checkInMapUrl()),
        $row('Check out', $fieldLog->hasCheckOutLocation(), $fieldLog->check_out_latitude, $fieldLog->check_out_longitude, $fieldLog->check_out_accuracy_m, $fieldLog->checkOutMapUrl()),
    ];
@endphp

<div class="border rounded p-3 mb-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0"><i class="ti ti-map-pin"></i> Location (GPS)</h6>
        @if($moved !== null)
            <small class="text-muted">{{ number_format($moved, 2) }} km between check-in and check-out</small>
        @endif
    </div>

    <div class="row g-3">
        @foreach($rows as $r)
            <div class="col-md-6">
                <small class="text-muted d-block">{{ $r['label'] }}</small>
                @if($r['has'])
                    <a href="{{ $r['url'] }}" target="_blank" rel="noopener">
                        {{ number_format((float) $r['lat'], 5) }}, {{ number_format((float) $r['lng'], 5) }}
                        <i class="ti ti-external-link"></i>
                    </a>
                    @if($r['acc'] !== null)
                        <span class="badge {{ $r['acc'] > $r['maxAcc'] ? 'bg-warning text-dark' : 'bg-light text-dark border' }}">&plusmn;{{ $r['acc'] }} m</span>
                        @if($r['acc'] > $r['maxAcc'])<small class="text-warning">low accuracy</small>@endif
                    @endif
                @else
                    <span class="text-muted">Not captured</span>
                @endif
            </div>
        @endforeach
    </div>
</div>
