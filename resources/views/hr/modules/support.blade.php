@extends('hr.layouts.app')

@section('title', $module['title'])

@section('content')
<style>
/* ---- Tabs ---- */
.ctabs {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    margin: 0 0 18px;
    border-bottom: 1px solid var(--line);
}

.ctab {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 16px;
    margin-bottom: -1px;
    border: 0;
    border-bottom: 2px solid transparent;
    background: transparent;
    color: var(--text-muted);
    font-family: var(--font-body);
    font-size: 13.5px;
    font-weight: 500;
    cursor: pointer;
    white-space: nowrap;
    transition: color .15s, border-color .15s, background .15s;
}

.ctab i {
    font-size: 13px;
}

.ctab:hover {
    color: var(--brand-ink);
    background: var(--brand-softer);
}

.ctab:focus-visible {
    outline: 2px solid var(--brand-bright);
    outline-offset: -2px;
}

.ctab.is-active {
    color: var(--brand-ink);
    border-bottom-color: var(--brand);
    font-weight: 600;
}

.ctab .ctab-count {
    min-width: 20px;
    padding: 1px 7px;
    border-radius: 999px;
    background: var(--brand-softer);
    color: var(--brand-ink);
    font-size: 11.5px;
    font-weight: 600;
    text-align: center;
}

.cpanel[hidden] {
    display: none;
}

@media (max-width: 640px) {
    .ctabs {
        flex-wrap: nowrap;
        overflow-x: auto;
    }
}
</style>
    @php
        $hasQueue = $allTicketsPage && $allTicketsPage->isNotEmpty();

        // Tabs this person can see: key => [label, icon, count badge]
        $tabs = [
            'mine' => ['My tickets', 'fa-regular fa-rectangle-list', $ownTickets->count()],
        ];
        if ($hasQueue) {
            $tabs['queue'] = ['Ticket queue', 'fa-solid fa-inbox', $notClosedCount > 0 ? $notClosedCount : null];
        }

        // Open ?tab= if given; paging the queue keeps the queue open.
        $requestedTab = request()->query('tab');
        if (is_string($requestedTab) && isset($tabs[$requestedTab])) {
            $activeTab = $requestedTab;
        } elseif (request()->filled('page') && isset($tabs['queue'])) {
            $activeTab = 'queue';
        } else {
            $activeTab = 'mine';
        }
    @endphp

    @include('hr.partials.topbar', [
        'title' => $module['title'],
        'eyebrow' => 'Comms',
        'heroIcon' => 'fa-regular fa-circle-question',
        'heroSummary' => 'Raise tickets for HR, IT, payroll or facilities — and track every request in one place.',
        'heroStats' => [
            ['label' => 'My tickets', 'icon' => 'fa-regular fa-rectangle-list', 'value' => $ownTickets->count()],
            ['label' => 'Open org-wide', 'icon' => 'fa-solid fa-inbox', 'value' => $openTicketsCount],
        ],
    ])

    <div class="content">
        {{-- ===== Tab bar (only when there is more than one tab) ===== --}}
        @if(count($tabs) > 1)
            <div class="ctabs" role="tablist" aria-label="Support sections">
                @foreach($tabs as $key => [$tabLabel, $tabIcon, $tabCount])
                    <button type="button" class="ctab {{ $key === $activeTab ? 'is-active' : '' }}"
                        role="tab" id="ctab-{{ $key }}" data-tab="{{ $key }}"
                        aria-controls="cpanel-{{ $key }}" aria-selected="{{ $key === $activeTab ? 'true' : 'false' }}"
                        tabindex="{{ $key === $activeTab ? '0' : '-1' }}">
                        <i class="{{ $tabIcon }}"></i>{{ $tabLabel }}
                        @if($tabCount !== null)<span class="ctab-count">{{ $tabCount }}</span>@endif
                    </button>
                @endforeach
            </div>
        @endif

        {{-- ===== Tab 1: My tickets (raise a ticket + own tickets) ===== --}}
        <div class="cpanel" id="cpanel-mine" data-panel="mine" role="tabpanel" aria-labelledby="ctab-mine" @if($activeTab !== 'mine') hidden @endif>
            <div class="grid-2">
                <div class="card">
                    <div class="widget-head">
                        <div class="wh-ico"><i class="fa-solid fa-headset"></i></div>
                        <div>
                            <h3>Raise a ticket</h3>
                            <p>HR, IT, Payroll, Facilities or Documents — routed to HR Admin / Super Admin.</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('hr.support.store') }}">
                        @csrf
                        <div class="field-grid">
                            <div class="field">
                                <label>Category</label>
                                <select name="category" required>
                                    <option value="it">IT</option>
                                    <option value="hr">HR</option>
                                    <option value="payroll">Payroll</option>
                                    <option value="facilities">Facilities</option>
                                    <option value="documents">Documents</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="field">
                                <label>Priority</label>
                                <select name="priority" required>
                                    <option value="normal" selected>Normal</option>
                                    <option value="low">Low</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                            <div class="field full"><label>Subject</label><input name="subject" placeholder="Brief summary" required></div>
                            <div class="field full"><label>Description</label><textarea name="description" placeholder="Details HR needs to help" required></textarea></div>
                        </div>
                        <div class="form-actions"><button type="submit" class="btn-primary">Raise ticket</button></div>
                    </form>
                </div>

                <div class="table-card">
                    <div class="tc-head">
                        <h3><span class="wh-ico"><i class="fa-regular fa-rectangle-list"></i></span>Your tickets</h3>
                        <span class="pill pill-muted">{{ $ownTickets->count() }} total</span>
                    </div>
                    <div class="tc-body">
                        @if($ownTickets->isEmpty())
                            <div class="empty-widget">
                                <div class="ew-ico"><i class="fa-regular fa-face-smile"></i></div>
                                <b>No tickets yet</b>
                                <span>Anything you raise will show up here with its status.</span>
                            </div>
                        @else
                            <table>
                                <thead><tr><th>Subject</th><th>Category</th><th>Status</th></tr></thead>
                                <tbody>
                                    @foreach($ownTickets as $t)
                                        <tr>
                                            <td>{{ \Illuminate\Support\Str::limit($t->subject, 30) }}</td>
                                            <td>{{ ucfirst($t->category) }}</td>
                                            <td>
                                                @php $p = ['open'=>'pill-warn','in_progress'=>'pill-warn','resolved'=>'pill-ok','closed'=>'pill-muted'][$t->status]; @endphp
                                                <span class="pill {{ $p }}">{{ ucfirst(str_replace('_',' ',$t->status)) }}</span>
                                            </td>
                                        </tr>
                                        @if($t->resolution_note)
                                            <tr><td colspan="3" style="color:var(--text-muted);font-size:12.5px;padding-top:0;"><i class="fa-solid fa-circle-info" style="color:var(--brand);margin-right:6px;"></i>{{ $t->resolution_note }}</td></tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== Tab 2: Ticket queue (HR Admin / Super Admin) ===== --}}
        @if($hasQueue)
            <div class="cpanel" id="cpanel-queue" data-panel="queue" role="tabpanel" aria-labelledby="ctab-queue" @if($activeTab !== 'queue') hidden @endif>
                <p class="field-hint" style="margin:0 0 12px;">Every ticket raised across the organization, open first</p>
                <div class="table-card">
                    <div class="tc-head">
                        <h3><span class="wh-ico"><i class="fa-solid fa-ticket"></i></span>Ticket queue</h3>
                        <span class="pill pill-warn">{{ $notClosedCount }} open</span>
                    </div>
                    <div class="tc-body">
                        <table>
                            <thead><tr><th>Employee</th><th>Category</th><th>Priority</th><th>Subject</th><th>Status</th><th></th></tr></thead>
                            <tbody>
                                @foreach($allTicketsPage as $t)
                                    <tr>
                                        <td>
                                            <div class="cell-emp">
                                                <div class="av">{{ strtoupper(substr($t->employee->user->name ?? '?',0,1)) }}</div>
                                                <div><b>{{ $t->employee->user->name ?? '—' }}</b></div>
                                            </div>
                                        </td>
                                        <td>{{ ucfirst($t->category) }}</td>
                                        <td>
                                            @php $pp = ['low'=>'pill-muted','normal'=>'pill-warn','high'=>'pill-bad'][$t->priority]; @endphp
                                            <span class="pill {{ $pp }}">{{ ucfirst($t->priority) }}</span>
                                        </td>
                                        <td>{{ \Illuminate\Support\Str::limit($t->subject, 28) }}</td>
                                        <td>
                                            @php $p = ['open'=>'pill-warn','in_progress'=>'pill-warn','resolved'=>'pill-ok','closed'=>'pill-muted'][$t->status]; @endphp
                                            <span class="pill {{ $p }}">{{ ucfirst(str_replace('_',' ',$t->status)) }}</span>
                                        </td>
                                        <td>
                                            @if($t->status !== 'closed')
                                                <form method="POST" action="{{ route('hr.support.update', $t) }}" style="display:flex;gap:6px;align-items:center;">
                                                    @csrf
                                                    <select name="status" style="padding:5px 8px;font-size:12px;">
                                                        <option value="open" @selected($t->status==='open')>Open</option>
                                                        <option value="in_progress" @selected($t->status==='in_progress')>In progress</option>
                                                        <option value="resolved" @selected($t->status==='resolved')>Resolved</option>
                                                        <option value="closed" @selected($t->status==='closed')>Closed</option>
                                                    </select>
                                                    <input name="resolution_note" placeholder="Resolution note" value="{{ $t->resolution_note }}" style="padding:5px 8px;font-size:12px;width:140px;">
                                                    <button type="submit" class="btn-ghost" style="padding:0;">Save</button>
                                                </form>
                                            @else
                                                <span class="field-hint">Closed</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $allTicketsPage->appends(['tab' => 'queue'])->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
    (function () {
        var tabs = Array.prototype.slice.call(document.querySelectorAll('.ctab'));
        var panels = Array.prototype.slice.call(document.querySelectorAll('.cpanel'));
        if (!tabs.length) return;

        function show(key, updateUrl) {
            tabs.forEach(function (t) {
                var on = t.getAttribute('data-tab') === key;
                t.classList.toggle('is-active', on);
                t.setAttribute('aria-selected', on ? 'true' : 'false');
                t.tabIndex = on ? 0 : -1;
            });
            panels.forEach(function (p) {
                p.hidden = p.getAttribute('data-panel') !== key;
            });
            if (updateUrl && window.history && window.history.replaceState) {
                var url = new URL(window.location.href);
                url.searchParams.set('tab', key);
                window.history.replaceState(null, '', url);
            }
        }

        tabs.forEach(function (t, i) {
            t.addEventListener('click', function () { show(t.getAttribute('data-tab'), true); });
            t.addEventListener('keydown', function (e) {
                var next = null;
                if (e.key === 'ArrowRight') next = tabs[(i + 1) % tabs.length];
                if (e.key === 'ArrowLeft') next = tabs[(i - 1 + tabs.length) % tabs.length];
                if (next) { e.preventDefault(); next.focus(); show(next.getAttribute('data-tab'), true); }
            });
        });
    })();
    </script>
@endsection