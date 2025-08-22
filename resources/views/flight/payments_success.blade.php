@extends('layout.layout')

@section('content')
<style>
    .success-circle {
        width: 60px;
        height: 60px;
        background: #28a745;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 30px;
        font-weight: bold;
    }
    .success-title {
        font-size: 1.8rem;
        font-weight: bold;
        color: #28a745; /* Bootstrap success color */
        margin: 0;
    }
    .more-toggle {
        padding: 6px 14px;
        font-size: 0.9rem;
    }
</style>

<div class="container my-4">

    {{-- Success Header with Light Green Background --}}
    <div class="p-4 shadow-sm rounded border mb-4" style="background-color: #d4edda;">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div class="d-flex align-items-center">
                <div class="success-circle me-3">✓</div>
                <div>
                    <h4 class="success-title">Booking Success</h4>
                    <p class="mb-0 text-success">
                        Booking ID: <strong>{{ $bookingId }}</strong><br>
                        <small class="text-muted">Please note down your reference ID for future use.</small>
                    </p>
                </div>
            </div>
            {{-- More Toggle Button --}}
            <button class="btn btn-success text-white fw-bold more-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#moreDetails">
                More
            </button>
        </div>

        {{-- Collapsible Extra Details --}}
        <div id="moreDetails" class="collapse mt-3">
            <div class="bg-light p-3 rounded shadow-sm text-start">
                <p class="mb-0">
                    Here you can add passenger details, fare breakdown, terms & conditions, or any other extra booking info.
                </p>
            </div>
        </div>
    </div>
    @foreach($flightDetails as $flight)
        @php
            $fromCity = $flight->departure_city;
            $toCity = $flight->arrival_city;
            $depTime = \Carbon\Carbon::parse($flight->departure_time)->format('M d, D, H:i');
            $arrTime = \Carbon\Carbon::parse($flight->arrival_time)->format('M d, D, H:i');
            $durationText = floor($flight->duration / 60) . 'h ' . ($flight->duration % 60) . 'm';
            $refundableText = $flight->is_refundable ? 'Refundable' : 'Non-Refundable';
            $logoPath = public_path("AirlinesLogo/{$flight->airline_code}.png");
            $logoUrl = file_exists($logoPath) ? asset("AirlinesLogo/{$flight->airline_code}.png") : asset("AirlinesLogo/default.png");
        @endphp

        <div class="bg-white shadow-sm rounded p-3 mb-4 border">
            <div class="d-flex justify-content-between border-bottom pb-2 mb-3">
                <div class="d-flex justify-content-between ">
                    <h6 class="fw-bold mb-0 me-2">{{ $fromCity }} → {{ $toCity }}</h6>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($flight->departure_time)->format('D, M jS Y') }}</small>
                </div>
            </div>

            <div class="row align-items-center mb-3">
                <div class="col-md-2 d-flex align-items-center">
                    <img src="{{ $logoUrl }}" alt="{{ $flight->airline_code }}" height="24" class="me-2">
                    <div>
                        <div class="text-muted small">{{ $flight->airline_name }}</div>
                        <div class="fw-bold small">{{ $flight->airline_code }}-{{ $flight->flight_number }}</div>
                    </div>
                </div>

                <div class="col-md-3 text-center">
                    <div class="fw-bold">{{ $depTime }}</div>
                    <small>{{ $fromCity }}</small><br>
                    <small class="text-muted">{{ $flight->departure_name }}</small>
                    @if($flight->departure_terminal)
                        <br><span class="text-muted">{{ $flight->departure_terminal }}</span>
                    @endif
                </div>

                <div class="col-md-2 text-center">
                    <div style="width: 100%; height: 2px; background-color: #f37321; margin: 5px 0; position: relative;">
                        <span style="position: absolute; right: -8px; top: -6px; color: #f37321;">→</span>
                    </div>
                </div>

                <div class="col-md-3 text-center">
                    <div class="fw-bold">{{ $arrTime }}</div>
                    <small>{{ $toCity }}</small><br>
                    <small class="text-muted">{{ $flight->arrival_name }}</small>
                    @if($flight->arrival_terminal)
                        <br><span class="text-muted">{{ $flight->arrival_terminal }}</span>
                    @endif
                </div>

                <div class="col-md-2 text-center">
                    <small class="text-muted d-block">{{ $durationText }}</small>
                    <small class="text-muted d-block">Economy</small>
                    <small class="text-muted d-block">{{ $refundableText }}</small>
                </div>
            </div>

            <div class="border-top pt-2">
                <small class="bg-warning bg-opacity-50 px-1 py-1 rounded">{{ ucfirst(strtolower($flight->fare_identifier ?? 'Published')) }}</small>
            </div>
            <div class="pt-1">
                <small class="text-muted">Baggage Information</small><br>
                <small class="text-muted mt-1">
                    Adult Cabin: {{ $flight->cabin_baggage ?? 'NA' }}{{ is_numeric($flight->cabin_baggage) ? ' Kg' : '' }},
                    Check-in: {{ $flight->checkin_baggage ?? 'NA' }}{{ is_numeric($flight->checkin_baggage) ? ' Kg' : '' }}
                </small>
            </div>
        </div>
    @endforeach

    <div class="bg-white shadow-sm rounded p-3 mb-4 border">
    <h6 class="fw-bold mb-3">
        Passenger Details ({{ count($passengerDetails) }})
    </h6>
    
    <table class="table table-bordered text-center small align-middle">
        <thead class="table-light">
            <tr>
                <th>Sr.</th>
                <th>Name, Age & Passport</th>
                <th>Seat Booking</th>
                <th>Meal & Baggage Preference</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($passengerDetails as $index => $passenger)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ strtoupper($passenger['title'] ?? '') }}
                        {{ strtoupper($passenger['first_name'] ?? '') }}
                        {{ strtoupper($passenger['last_name'] ?? '') }}
                        @if (!empty($passenger['dob']))
                            <br>
                            <small>{{ \Carbon\Carbon::parse($passenger['dob'])->format('d/m/Y') }}</small>
                        @endif
                    </td>
                    <td>NA</td>
                    <td>
                        @php
                            $baggage = $passenger['baggage'] ?? 'NA';
                            $meal = $passenger['meal'] ?? null;
                            $from = $passenger['from'] ?? 'NA';
                            $to = $passenger['to'] ?? 'NA';
                        @endphp

                        <i class="fas fa-suitcase-rolling me-1"></i>
                        {{ $from }} → {{ $to }} +{{ $baggage }} Xcess Baggage 
                        
                        @if ($meal)
                            <br><small>Meal: {{ $meal }}</small>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Contact Details --}}
    <div class="mt-3">
        <h6 class="fw-bold">Contact Details</h6>
        <p class="mb-1">Email: 
            <a href="mailto:{{ $contactDetails['email'] ?? 'NA' }}">
                {{ $contactDetails['email'] ?? 'NA' }}
            </a>
        </p>
        <p>Mobile: {{ $contactDetails['mobile'] ?? 'NA' }}</p>
    </div>

    <div class="form-check mt-2">
        <input class="form-check-input" type="checkbox" required id="agreeTerms">
        <label class="form-check-label small" for="agreeTerms">
            By proceeding, I acknowledge and agree to the 
            <a href="#">terms & conditions</a>.
        </label>
    </div>
</div>

    <!-- View Invoice Button -->
<div class="mt-4">
    <a href="{{ route('invoice.show', $bookingId) }}" class="btn btn-primary">
        View Invoice
    </a>
</div>

@endsection
