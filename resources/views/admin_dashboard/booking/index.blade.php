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
                                                BookingID-{{ $booking->id ?? 'Tj123456' }}
                                            </small>
                                        </div>
                                    </div>
                                    <h5 class="fw-bold mb-0">₹ {{ number_format($booking->payment?->amount ?? 0, 2) }}</h5>
                                    <button class="btn btn-outline-warning btn-sm">Actions ▾</button>
                                </div>

                                <!-- Flight Info -->
                                <div class="row mt-4">
                                    <!-- From -->
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
                                        <p class="text-muted mb-1">Ticket Number</p>
                                        <p class="fw-medium mb-0">{{ $booking->ticket_number ?? '—' }}</p>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-4 col-lg-2 mb-3">
                                        <p class="text-muted mb-1">Status</p>
                                        <span class="badge {{ $booking->payment?->status == 'Paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                            {{ $booking->payment?->status ?? 'Unpaid' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3">
                    {{ $bookings->links() }}
                </div>
            </div>

            <!-- Completed -->
            <div class="tab-pane fade" id="completed">
                @foreach($bookings->filter(fn($b) => $b->payment?->status === 'Completed') as $booking)
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <p class="fw-semibold">{{ $booking->flightDetail?->airline_name ?? 'Unknown Airline' }}</p>
                            <span class="badge bg-success">Completed</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Processing -->
            <div class="tab-pane fade" id="processing">
                @foreach($bookings->filter(fn($b) => $b->payment?->status === 'Processing') as $booking)
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <p class="fw-semibold">{{ $booking->flightDetail?->airline_name ?? 'Unknown Airline' }}</p>
                            <span class="badge bg-info">Processing</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Confirmed -->
            <div class="tab-pane fade" id="confirmed">
                @foreach($bookings->filter(fn($b) => $b->payment?->status === 'Confirmed') as $booking)
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <p class="fw-semibold">{{ $booking->flightDetail?->airline_name ?? 'Unknown Airline' }}</p>
                            <span class="badge bg-primary">Confirmed</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Cancelled -->
            <div class="tab-pane fade" id="cancelled">
                @foreach($bookings->filter(fn($b) => $b->payment?->status === 'Cancelled') as $booking)
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <p class="fw-semibold">{{ $booking->flightDetail?->airline_name ?? 'Unknown Airline' }}</p>
                            <span class="badge bg-danger">Cancelled</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paid -->
            <div class="tab-pane fade" id="paid">
                @foreach($bookings->filter(fn($b) => $b->payment?->status === 'Paid') as $booking)
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <p class="fw-semibold">{{ $booking->flightDetail?->airline_name ?? 'Unknown Airline' }}</p>
                            <span class="badge bg-success">Paid</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Unpaid -->
            <div class="tab-pane fade" id="unpaid">
                @foreach($bookings->filter(fn($b) => $b->payment?->status === 'Unpaid') as $booking)
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <p class="fw-semibold">{{ $booking->flightDetail?->airline_name ?? 'Unknown Airline' }}</p>
                            <span class="badge bg-warning text-dark">Unpaid</span>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</div>
@endsection
