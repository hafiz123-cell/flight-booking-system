@extends('admin_dashboard.layouts.vertical', ['subtitle' => 'Configuration'])

@section('content')
@include('admin_dashboard.layouts.partials.page-title', ['title' => 'Configuration', 'subtitle' => 'Tripjack Settings'])

<div class="card p-4">
    <h4 class="mb-3">Tripjack API Mode</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <p>Current Mode: 
        <span class="badge {{ $currentMode === 'live' ? ' bg-success fs-6 p-2 rounded-sm opacity-50 ' : 'bg-warning  fs-6 p-2 rounded-sm opacity-50' }}">
            {{ ucfirst($currentMode) }}
        </span>
    </p>

    <form action="{{ route('config.setMode') }}" method="POST">
        @csrf
        <div class="d-flex gap-3">
            <button type="submit" name="mode" value="live" class="btn btn-success px-4 py-2">
                Live
            </button>
            <button type="submit" name="mode" value="test" class="btn btn-warning px-4 py-2">
                Test
            </button>
        </div>
    </form>
</div>
@endsection
