@extends('admin_dashboard.layouts.vertical', ['subtitle' => 'Bookings Table'])

@section('content')
@include('admin_dashboard.layouts.partials.page-title', ['title' => 'Tables', 'subtitle' => 'Bookings'])

<div class="card p-4">
    <div class="container">
        <!-- Tabs -->
        <ul class="nav nav-tabs mb-4" id="bookingTabs" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#all">All Booking</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#completed">Completed</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#processing">Processing</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#confirmed">Confirmed</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#cancelled">Cancelled</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#paid">Paid</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#unpaid">Unpaid</button></li>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content" id="bookingTabsContent">

            <!-- All Bookings -->
            <div class="tab-pane fade show active" id="all">
                <div id="bookingsTable">
                    @foreach($bookings as $booking)
                        <div class="card shadow-sm mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-2">✈️</div>
                                        <div>
                                            <p class="fw-semibold mb-0">{{ $booking->flightDetail?->airline_name ?? 'Unknown Airline' }}</p>
                                            <small class="text-muted">
                                                <strong>{{ Str::ucfirst($booking->flightDetail?->type ?? 'One way') }}</strong> · 
                                                BookingID-{{  $booking->flightDetail?->booking_id ?? 'Tj123456' }}
                                            </small>
                                        </div>
                                    </div>
                                    <h5 class="fw-bold mb-0">₹ {{ number_format($booking->payment?->amount ?? 0, 2) }}</h5>
                                  <div class="dropdown">
    <button class="btn btn-outline-warning btn-sm dropdown-toggle" type="button" id="actionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        Actions
    </button>
    <ul class="dropdown-menu" aria-labelledby="actionsDropdown">
        <li>
            <a class="dropdown-item" href="{{ route('invoice.show', $booking->flightDetail?->booking_id) }}">View Invoice</a>
        </li>
       
    </ul>
</div>

                                </div>

                                <!-- Flight Info -->
                                <div class="row mt-4">
                                    <!-- From -->
                                     @if ($booking->flightDetail?->type =='roundtrip')
                                          <p class="text-muted mb-1">Depart:</p>
                                        @endif
                                    <div class="col-md-4 col-lg-2 mb-3">
                                       
                                        <p class="text-muted mb-1">From</p>
                                        <p class="fw-medium mb-0">
                                            {{ $booking->flightDetail?->departure_time ? \Carbon\Carbon::parse($booking->flightDetail->departure_time)->format('D, d M y H:i') : '' }}
                                        </p>
                                        <small class="text-muted">{{ $booking->flightDetail?->departure_name ?? '' }}</small>
                                    </div>

                                    <!-- To -->
                                    <div class="col-md-4 col-lg-2 mb-3">
                                        <p class="text-muted mb-1">To</p>
                                        <p class="fw-medium mb-0">
                                            {{ $booking->flightDetail?->arrival_time ? \Carbon\Carbon::parse($booking->flightDetail->arrival_time)->format('D, d M y H:i') : '' }}
                                        </p>
                                        <small class="text-muted">{{ $booking->flightDetail?->arrival_name ?? '' }}</small>
                                    </div>

                                   

                                    <!-- Duration -->
                                    <div class="col-md-4 col-lg-2 mb-3">
                                        <p class="text-muted mb-1">Duration</p>
                                        @php
                                            if ($booking->flightDetail?->departure_time && $booking->flightDetail?->arrival_time) {
                                                $departure = \Carbon\Carbon::parse($booking->flightDetail->departure_time);
                                                $arrival = \Carbon\Carbon::parse($booking->flightDetail->arrival_time);
                                                $duration = $departure->diff($arrival);
                                            }
                                        @endphp
                                        <p class="fw-medium mb-0">{{ isset($duration) ? $duration->h . ' h : ' . $duration->i . ' m' : '' }}</p>
                                    </div>

                                    <!-- Order Date -->
                                    <div class="col-md-4 col-lg-2 mb-3">
                                        <p class="text-muted mb-1">Order Date</p>
                                        <p class="fw-medium mb-0">{{ $booking->created_at->format('m/d/Y') }}</p>
                                    </div>

                                    <!-- Ticket Number -->
                                    <div class="col-md-4 col-lg-2 mb-3">
                                        <p class="text-muted mb-1">User</p>
                                        <p class="fw-medium mb-0">{{ $booking->payment?->firstname ?? '—' }}</p>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-4 col-lg-2 mb-3">
                                        <p class="text-muted mb-1">Status</p>
                                        <span class="badge {{ $booking->payment?->status === 'success' ? ' bg-success fs-6 p-2 rounded-sm opacity-50 ' : 'bg-warning  fs-6 p-2 rounded-sm opacity-50' }}">
    {{ $booking->payment?->status === 'success' ? 'Paid' : ($booking->payment?->status ?? 'Unpaid') }}
</span>
                                    </div>
                                </div>
                              @if($returnFlights && $returnFlights->where('onward_flight_id', $booking->flight_detail_id)->count() > 0)
    <p class="text-muted mb-1">Return:</p>
    <div class="col-md-12 mb-3">
        @foreach($returnFlights->where('onward_flight_id', $booking->flight_detail_id) as $rf)
            <div class="row">
                <!-- From -->
                <div class="col-md-4 col-lg-2 mb-3">
                    <p class="fw-medium mb-0">
                        {{ \Carbon\Carbon::parse($rf->departure_time)->format('D, d M y H:i') }}
                    </p>
                    <small class="text-muted">{{ $rf->departure_name ?? '' }}</small>
                </div>

                <!-- To -->
                <div class="col-md-4 col-lg-2 mb-3">
                    <p class="fw-medium mb-0">
                        {{ \Carbon\Carbon::parse($rf->arrival_time)->format('D, d M y H:i') }}
                    </p>
                    <small class="text-muted">{{ $rf->arrival_name ?? '' }}</small>
                </div>
            </div>
        @endforeach
    </div>
@endif

                            </div>
                             <!-- Return Flight(s) -->
                                  
                        </div>
                    @endforeach
                    
                </div>
                <div class="mt-3">
                    {{ $bookings->links() }}
                </div>
            </div>

            <!-- Other Tabs (Completed, Processing, Confirmed, Cancelled, Paid, Unpaid) -->
            @foreach(['completed','processing','confirmed','cancelled','paid','unpaid'] as $statusTab)
                <div class="tab-pane fade" id="{{ $statusTab }}">
                    @foreach($bookings->filter(fn($b) => strtolower($b->payment?->status ?? '') === $statusTab) as $booking)
                        <div class="card shadow-sm mb-3">
                            <div class="card-body">
                                <p class="fw-semibold">{{ $booking->flightDetail?->airline_name ?? 'Unknown Airline' }}</p>
                                @if($booking->returnFlights && $booking->returnFlights->count() > 0)
                                    <div>
                                        <small>Return Flight(s):</small>
                                        @foreach($booking->returnFlights as $rf)
                                            <p class="mb-0">{{ $rf->flight_number }}: {{ $rf->departure_name }} → {{ $rf->arrival_name }}</p>
                                        @endforeach
                                    </div>
                                @endif
                                <span class="badge 
                                    @if($statusTab==='paid') bg-success 
                                    @elseif($statusTab==='unpaid') bg-warning text-dark
                                    @elseif($statusTab==='cancelled') bg-danger
                                    @elseif($statusTab==='confirmed') bg-primary
                                    @elseif($statusTab==='processing') bg-info
                                    @else bg-success @endif">{{ ucfirst($statusTab) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

        </div>
    </div>
</div>
@endsection
