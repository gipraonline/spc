@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="mb-1">Notifications</h4>
        <p class="text-muted small mb-4">Sales, order and other alerts. HR notifications have their own list under the HR module.</p>

        @forelse($notifications as $n)
            <div class="d-flex justify-content-between align-items-start border-bottom py-3">
                <div>
                    <span class="badge {{ match($n->category) {
                        'order' => 'bg-warning text-dark',
                        'sales' => 'bg-success',
                        default => 'bg-secondary',
                    } }} me-2">{{ ucfirst($n->category) }}</span>
                    @if($n->title)<strong>{{ $n->title }}</strong> &middot; @endif
                    {{ $n->message }}
                    <div class="text-muted small mt-1">{{ $n->created_at->diffForHumans() }}</div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @if($n->link)
                        <a href="{{ $n->link }}" class="btn btn-sm btn-outline-primary">Open</a>
                    @endif
                    @if(!$n->read_at)
                        <form method="POST" action="{{ route('notifications.read', $n) }}">
                            @csrf
                            <button class="btn btn-sm btn-outline-secondary">Mark read</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-muted">No notifications yet.</p>
        @endforelse

        <div class="mt-3">{{ $notifications->links() }}</div>
    </div>
</div>
@endsection
