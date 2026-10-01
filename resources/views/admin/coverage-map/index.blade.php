@extends('layouts.app')

@push('styles')
<style>
.cm{--sink:#1F3D14;--smut:#61756B;--sline:rgba(18,58,40,.12);}
.cm-head{display:flex;justify-content:space-between;align-items:flex-end;gap:14px;flex-wrap:wrap;margin-bottom:14px;}
.cm-head h2{margin:0;font-family:var(--font-head,'Kanit',sans-serif);font-size:22px;font-weight:600;color:var(--sink);}
.cm-head p{margin:4px 0 0;font-size:13px;color:var(--smut);}
.cm-head select{height:40px;border:1px solid #d5dde3;border-radius:10px;padding:0 12px;background:#fff;}
.cm-kpis{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px;margin-bottom:14px;}
.cm-kpi{background:#fff;border:1px solid var(--sline);border-radius:14px;padding:12px 16px;}
.cm-kpi b{display:block;font-size:22px;color:var(--sink);}.cm-kpi span{font-size:12px;color:var(--smut);}
.cm-kpi.bad b{color:#B42318;}
#coverageMap{height:560px;border-radius:16px;border:1px solid var(--sline);z-index:0;}
.cm-legend{display:flex;gap:18px;flex-wrap:wrap;margin:10px 0 18px;font-size:12.5px;color:var(--smut);}
.cm-legend i{display:inline-block;width:11px;height:11px;border-radius:50%;margin-right:6px;vertical-align:-1px;}
.cm-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:14px;}
.cm-card{background:#fff;border:1px solid var(--sline);border-radius:16px;overflow:hidden;}
.cm-card h3{margin:0;padding:13px 16px;font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--smut);border-bottom:1px solid var(--sline);}
.cm-card table{width:100%;border-collapse:collapse;font-size:13.5px;}
.cm-card td,.cm-card th{padding:10px 16px;border-top:1px solid #f1f5f9;text-align:left;}
.cm-card th{border-top:0;font-size:11px;color:#64748b;text-transform:uppercase;}
.cm-note{padding:12px 16px;font-size:12.5px;color:var(--smut);}
</style>
@endpush

@section('content')
<div class="cm">
    <div class="cm-head">
        <div>
            <h2>Coverage Map</h2>
            <p>Orders against active franchises. Orange = more than {{ rtrim(rtrim(number_format($threshold, 1), '0'), '.') }} km from every franchise.</p>
        </div>
        <form method="GET">
            <select name="days" onchange="this.form.submit()" aria-label="Period">
                @foreach([30 => 'Last 30 days', 90 => 'Last 90 days', 180 => 'Last 6 months', 365 => 'Last 12 months'] as $d => $label)
                    <option value="{{ $d }}" @selected($days === $d)>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="cm-kpis">
        <div class="cm-kpi"><b>{{ $stats['mapped'] }}</b><span>Orders on the map</span></div>
        <div class="cm-kpi bad"><b>{{ $stats['underserved'] }}</b><span>Under-served orders</span></div>
        <div class="cm-kpi"><b>{{ $stats['unmapped'] }}</b><span>Orders without a location</span></div>
        <div class="cm-kpi"><b>{{ $stats['franchises'] }}</b><span>Active franchises with a location</span></div>
    </div>
    @if($stats['capped'])
        <p style="font-size:12.5px;color:#B36B00;">Showing the latest {{ $stats['mapped'] }} located orders of {{ $stats['total'] }}. Choose a shorter period to see all.</p>
    @endif

    <div id="coverageMap"></div>
    <div class="cm-legend">
        <span><i style="background:#1d4ed8;border-radius:3px;"></i>Franchise</span>
        <span><i style="background:#2f7d4f;"></i>Order within range</span>
        <span><i style="background:#d97706;"></i>Order beyond {{ rtrim(rtrim(number_format($threshold, 1), '0'), '.') }} km</span>
    </div>

    <div class="cm-grid">
        <div class="cm-card">
            <h3>Where the gaps are</h3>
            @if(count($byDistrict))
                <table>
                    <thead><tr><th>District</th><th>Under-served orders</th></tr></thead>
                    <tbody>@foreach($byDistrict as $district => $n)<tr><td>{{ $district }}</td><td><b>{{ $n }}</b></td></tr>@endforeach</tbody>
                </table>
                <div class="cm-note">Districts with many orders far from any franchise are candidates for a new franchise.</div>
            @else
                <div class="cm-note">No under-served orders in this period.</div>
            @endif
        </div>
        <div class="cm-card">
            <h3>Busiest franchises</h3>
            @if($perFranchise->isNotEmpty())
                <table>
                    <thead><tr><th>Franchise</th><th>Orders</th><th>Avg km</th></tr></thead>
                    <tbody>@foreach($perFranchise as $f)<tr><td>{{ $f->name }}</td><td><b>{{ $f->orders }}</b></td><td>{{ $f->avg_km ?? '—' }}</td></tr>@endforeach</tbody>
                </table>
            @else
                <div class="cm-note">No franchise-assigned orders in this period.</div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    const franchises = @json($franchises);
    const points = @json($points);
    const map = L.map('coverageMap');
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18, attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
    const bounds = [];

    franchises.forEach(f => {
        L.marker([f.lat, f.lng], {
            icon: L.divIcon({className: '', html: '<div style="width:16px;height:16px;background:#1d4ed8;border:2px solid #fff;border-radius:4px;box-shadow:0 0 0 1px rgba(0,0,0,.3)"></div>', iconSize: [16, 16]})
        }).addTo(map).bindPopup('<b>' + esc(f.name) + '</b><br>' + esc(f.code || ''));
        L.circle([f.lat, f.lng], {radius: {{ (float) $threshold }} * 1000, color: '#2f7d4f', weight: 1, dashArray: '5 5', fillOpacity: 0.03}).addTo(map);
        bounds.push([f.lat, f.lng]);
    });

    points.forEach(p => {
        L.circleMarker([p.lat, p.lng], {
            radius: 5, weight: 1, color: '#fff', fillOpacity: .85, fillColor: p.far ? '#d97706' : '#2f7d4f'
        }).addTo(map).bindPopup(
            '<b>' + esc(p.no) + '</b><br>' + esc(p.date) + (p.district ? ' · ' + esc(p.district) : '') +
            '<br>₹' + Number(p.amount || 0).toLocaleString('en-IN') +
            '<br>' + (p.km === null ? 'No franchise location on file' : 'Nearest franchise: ' + p.km + ' km')
        );
        bounds.push([p.lat, p.lng]);
    });

    if (bounds.length) { map.fitBounds(bounds, {padding: [30, 30], maxZoom: 12}); } else { map.setView([10.5, 76.3], 7); }
})();
</script>
@endpush
