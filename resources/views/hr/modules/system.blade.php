@extends('hr.layouts.app')

@section('title', $module['title'])

@section('content')
@include('hr.partials.topbar', [
'title' => $module['title'],
'eyebrow' => 'System',
'heroIcon' => 'fa-solid fa-shield-halved',
'heroSummary' => 'A record of who changed what, and when.',
'heroStats' => [
['label' => 'Entries', 'icon' => 'fa-solid fa-timeline', 'value' => $totalAuditEntries],
['label' => 'Today', 'icon' => 'fa-regular fa-calendar', 'value' => $todayCount],
],
])

<div class="content">
    <style>
    .al-wrap {
        max-width: 900px;
    }

    .al-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 20px;
    }

    .al-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border: 1px solid var(--line);
        border-radius: 999px;
        background: #fff;
        color: var(--text);
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        transition: border-color .15s, background .15s;
    }

    .al-chip small {
        font-size: 11.5px;
        color: var(--text-muted);
        font-weight: 600;
    }

    .al-chip:hover {
        border-color: var(--brand-bright);
        background: var(--brand-softer);
    }

    .al-chip.on {
        background: var(--brand);
        border-color: var(--brand);
        color: #fff;
    }

    .al-chip.on small {
        color: rgba(255, 255, 255, .8);
    }

    .al-day {
        margin: 26px 0 10px;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-muted);
    }

    .al-day:first-of-type {
        margin-top: 0;
    }

    .al-list {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        overflow: hidden;
    }

    .al-item {
        display: grid;
        grid-template-columns: 40px minmax(0, 1fr) auto;
        gap: 14px;
        padding: 16px 20px;
        border-bottom: 1px solid var(--line-soft);
        align-items: start;
    }

    .al-item:last-child {
        border-bottom: none;
    }

    .al-ico {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .al-ico.add {
        background: var(--ok-soft);
        color: var(--ok);
    }

    .al-ico.edit {
        background: var(--warn-soft);
        color: var(--warn);
    }

    .al-ico.remove {
        background: var(--bad-soft);
        color: var(--bad);
    }

    .al-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
        line-height: 1.4;
    }

    .al-lines {
        margin: 4px 0 0;
        padding: 0;
        list-style: none;
        font-size: 13px;
        color: var(--text-muted);
        line-height: 1.6;
    }

    .al-by {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
        font-size: 12.5px;
        color: var(--text-muted);
    }

    .al-by .av {
        width: 22px;
        height: 22px;
        font-size: 11px;
    }

    .al-time {
        font-size: 12.5px;
        color: var(--text-muted);
        white-space: nowrap;
        text-align: right;
    }

    .al-raw {
        margin-top: 8px;
    }

    .al-raw summary {
        cursor: pointer;
        font-size: 12px;
        color: var(--brand);
        font-weight: 600;
        list-style: none;
    }

    .al-raw summary::-webkit-details-marker {
        display: none;
    }

    .al-raw pre {
        margin: 8px 0 0;
        padding: 10px 12px;
        background: var(--brand-softer);
        border-radius: 10px;
        font-size: 11.5px;
        overflow-x: auto;
        white-space: pre-wrap;
        word-break: break-word;
        color: var(--text);
    }

    .al-pager {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: 16px;
        font-size: 13px;
        color: var(--text-muted);
    }

    .al-pager .btns {
        display: flex;
        gap: 8px;
    }

    .al-pager a,
    .al-pager span.dis {
        padding: 8px 14px;
        border: 1px solid var(--line);
        border-radius: 10px;
        background: #fff;
        color: var(--text);
        text-decoration: none;
        font-weight: 500;
    }

    .al-pager a:hover {
        border-color: var(--brand-bright);
        background: var(--brand-softer);
    }

    .al-pager span.dis {
        opacity: .4;
    }

    @media (max-width:640px) {
        .al-item {
            grid-template-columns: 36px minmax(0, 1fr);
        }

        .al-time {
            grid-column: 2;
            text-align: left;
        }
    }
    </style>

    <div class="al-wrap">
        <nav class="al-chips" aria-label="Filter by area">
            <a class="al-chip @if(! $area) on @endif" href="{{ route('hr.system.index') }}">All
                <small>{{ $areaCounts['all'] }}</small></a>
            @foreach($areas as $key => $label)
            @if($areaCounts[$key] > 0 || $area === $key)
            <a class="al-chip @if($area === $key) on @endif"
                href="{{ route('hr.system.index', ['area' => $key]) }}">{{ $label }}
                <small>{{ $areaCounts[$key] }}</small></a>
            @endif
            @endforeach
        </nav>

        @if($auditLog->isEmpty())
        <div class="table-card">
            <div class="tc-body">
                <div class="empty-widget">
                    <div class="ew-ico"><i class="fa-solid fa-timeline"></i></div>
                    <b>{{ $area ? 'Nothing here yet' : 'No activity recorded yet' }}</b>
                    <span>{{ $area ? 'No changes have been recorded in this area.' : 'Changes to payroll, settings and access will appear here.' }}</span>
                </div>
            </div>
        </div>
        @else
        @php
        $days = $auditLog->getCollection()->groupBy(fn ($l) =>
        \Illuminate\Support\Carbon::parse($l->created_at)->toDateString());
        @endphp

        @foreach($days as $date => $entries)
        @php
        $d = \Illuminate\Support\Carbon::parse($date);
        $dayLabel = $d->isToday() ? 'Today' : ($d->isYesterday() ? 'Yesterday' : $d->format('l, d M Y'));
        @endphp
        <h3 class="al-day">{{ $dayLabel }}</h3>
        <div class="al-list">
            @foreach($entries as $log)
            @php $sm = $log->summary; $who = $log->user->name ?? 'System'; @endphp
            <div class="al-item">
                <div class="al-ico {{ $sm['tone'] }}"><i class="fa-solid {{ $sm['icon'] }}"></i></div>
                <div>
                    <div class="al-title">{{ $sm['title'] }}</div>
                    @if($sm['lines'])
                    <ul class="al-lines">
                        @foreach($sm['lines'] as $line)<li>{{ $line }}</li>@endforeach
                    </ul>
                    @endif
                    <div class="al-by">
                        <div class="av">{{ strtoupper(substr($who, 0, 1)) }}</div>
                        <span>by {{ $who }}@if($log->ip_address) &middot; {{ $log->ip_address }}@endif</span>
                    </div>
                    @if($log->old_value || $log->new_value)
                    <details class="al-raw">
                        <summary>View technical details</summary>
                        <pre>@if($log->old_value)Before: {{ $log->old_value }}
@endif @if($log->new_value)After:  {{ $log->new_value }}@endif</pre>
                    </details>
                    @endif
                </div>
                <div class="al-time"
                    title="{{ \Illuminate\Support\Carbon::parse($log->created_at)->format('d M Y, H:i:s') }}">
                    {{ \Illuminate\Support\Carbon::parse($log->created_at)->format('h:i A') }}</div>
            </div>
            @endforeach
        </div>
        @endforeach

        <div class="al-pager">
            <span>Showing {{ $auditLog->firstItem() }}&ndash;{{ $auditLog->lastItem() }} of
                {{ $auditLog->total() }}</span>
            @if($auditLog->hasPages())
            <div class="btns">
                @if($auditLog->onFirstPage())<span class="dis">Newer</span>@else<a
                    href="{{ $auditLog->previousPageUrl() }}">Newer</a>@endif
                @if($auditLog->hasMorePages())<a href="{{ $auditLog->nextPageUrl() }}">Older</a>@else<span
                    class="dis">Older</span>@endif
            </div>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection