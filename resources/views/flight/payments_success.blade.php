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
            <button class="btn btn-success opacity-65 text-white fw-bold more-toggle" type="button">
                <a  href="{{ route('invoice.show', $bookingId) }}" class="text-white">
        View Invoice
    </a>
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
        // Onward Flight
        $fromCity = $flight->departure_city;
        $toCity = $flight->arrival_city;
        $depTime = \Carbon\Carbon::parse($flight->departure_time)->format('M d, D, H:i');
        $arrTime = \Carbon\Carbon::parse($flight->arrival_time)->format('M d, D, H:i');
        $durationText = floor($flight->duration / 60) . 'h ' . ($flight->duration % 60) . 'm';
        $refundableText = $flight->is_refundable ? 'Refundable' : 'Non-Refundable';
        $logoPath = public_path("AirlinesLogo/{$flight->airline_code}.png");
        $logoUrl = file_exists($logoPath) ? asset("AirlinesLogo/{$flight->airline_code}.png") : asset("AirlinesLogo/default.png");
    @endphp

    {{-- Onward Flight Card --}}
    <div class="bg-white shadow-sm rounded p-3 mb-4 border">
        <div class="d-flex justify-content-between border-bottom pb-2 mb-3 p-2" style="background-color:rgba(228, 224, 224, 0.8); border-radius:5px;">
            <div class="d-flex justify-content-between">
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
            <small class="bg-warning bg-opacity-50 px-1 py-1 rounded">
                {{ ucfirst(strtolower($flight->fare_identifier ?? 'Published')) }}
            </small>
        </div>
        <div class="pt-1 d-flex">
            <small class="text-muted me-2">Baggage Information</small><br>
            <small class="text-muted">
                Adult Cabin: {{ $flight->cabin_baggage ?? 'NA' }}{{ is_numeric($flight->cabin_baggage) ? ' Kg' : '' }},
                Check-in: {{ $flight->checkin_baggage ?? 'NA' }}{{ is_numeric($flight->checkin_baggage) ? ' Kg' : '' }}
            </small>
        </div>
   <div style="border-bottom:1px solid rgba(228, 224, 224, 0.8);"></div>

    {{-- Return Flight(s) for this Onward --}}
    @php
        $relatedReturns = $returnFlight->where('onward_flight_id', $flight->id);
    @endphp

    @foreach($relatedReturns as $return)
        @php
            $fromCity = $return->departure_city;
            $toCity = $return->arrival_city;
            $depTime = \Carbon\Carbon::parse($return->departure_time)->format('M d, D, H:i');
            $arrTime = \Carbon\Carbon::parse($return->arrival_time)->format('M d, D, H:i');
            $durationText = floor($return->duration / 60) . 'h ' . ($return->duration % 60) . 'm';
            $refundableText = $return->is_refundable ? 'Refundable' : 'Non-Refundable';
            $logoPath = public_path("AirlinesLogo/{$return->airline_code}.png");
            $logoUrl = file_exists($logoPath) ? asset("AirlinesLogo/{$return->airline_code}.png") : asset("AirlinesLogo/default.png");
        @endphp

       
            <div class="d-flex justify-content-between border-bottom mt-4 pb-2 mb-3 p-2" style="background-color:rgba(228, 224, 224, 0.8); border-radius:5px;">
                <div class="d-flex justify-content-between">
                    <h6 class="fw-bold mb-0 me-2"> {{ $fromCity }} → {{ $toCity }}</h6>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($return->departure_time)->format('D, M jS Y') }}</small>
                </div>
            </div>

            <div class="row align-items-center mb-3">
                <div class="col-md-2 d-flex align-items-center">
                    <img src="{{ $logoUrl }}" alt="{{ $return->airline_code }}" height="24" class="me-2">
                    <div>
                        <div class="text-muted small">{{ $return->airline_name }}</div>
                        <div class="fw-bold small">{{ $return->airline_code }}-{{ $return->flight_number }}</div>
                    </div>
                </div>

                <div class="col-md-3 text-center">
                    <div class="fw-bold">{{ $depTime }}</div>
                    <small>{{ $fromCity }}</small><br>
                    <small class="text-muted">{{ $return->departure_name }}</small>
                    @if($return->departure_terminal)
                        <br><span class="text-muted">{{ $return->departure_terminal }}</span>
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
                    <small class="text-muted">{{ $return->arrival_name }}</small>
                    @if($return->arrival_terminal)
                        <br><span class="text-muted">{{ $return->arrival_terminal }}</span>
                    @endif
                </div>

                <div class="col-md-2 text-center">
                    <small class="text-muted d-block">{{ $durationText }}</small>
                    <small class="text-muted d-block">Economy</small>
                    <small class="text-muted d-block">{{ $refundableText }}</small>
                </div>
            </div>

            <div class="border-top pt-2">
                <small class="bg-warning bg-opacity-50 px-1 py-1 rounded">
                    {{ ucfirst(strtolower($return->fare_identifier ?? 'Published')) }}
                </small>
            </div>
            <div class="pt-1 d-flex">
                <small class="text-muted">Baggage Information</small><br>
                <small class="text-muted">
                    Adult Cabin: {{ $return->cabin_baggage ?? 'NA' }}{{ is_numeric($return->cabin_baggage) ? ' Kg' : '' }},
                    Check-in: {{ $return->checkin_baggage ?? 'NA' }}{{ is_numeric($return->checkin_baggage) ? ' Kg' : '' }}
                </small>
            </div>
        </div>
    @endforeach
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
    // Onward flight codes (first flight in onward collection)
    $onwardFrom = $flightDetails->first()->departure_code ?? 'NA';
    $onwardTo   = $flightDetails->last()->arrival_code ?? 'NA';

    // Return flight codes (first flight in return collection)
    $returnFrom = $returnFlight->first()->departure_code ?? 'NA';
    $returnTo   = $returnFlight->last()->arrival_code ?? 'NA';
@endphp


                    @if (!empty($passenger['flights']))
                        {{-- Onward Flight (first flight) --}}
                        @if (!empty($passenger['flights'][0]))
                            @php
                                $onward = $passenger['flights'][0];
                            @endphp
                            <div class="mb-2">
                                <strong>{{ $onwardFrom }} → {{ $onwardTo }}</strong><br>
                                <i class="fas fa-suitcase-rolling me-1"></i>
                                {{ $onward['baggage'] ?? 'NA' }}
                                @if (!empty($onward['meal']))
                                    <br><small>🍴 Meal: {{ $onward['meal'] }}</small>
                                @endif
                                 @if (!$loop->last)
                            
                            <hr class="my-1">
                         
                            @endif
                            </div>
                        @endif

                        {{-- Return Flight (second flight) --}}
                        @if (!empty($passenger['flights'][1]))
                            @php
                                $return = $passenger['flights'][1];
                            @endphp
                            <div>
                                <strong>{{ $returnFrom }} → {{ $returnTo }}</strong><br>
                                <i class="fas fa-suitcase-rolling me-1"></i>
                                {{ $return['baggage'] ?? 'NA' }}
                                @if (!empty($return['meal']))
                                    <br><small>🍴 Meal: {{ $return['meal'] }}</small>
                                     
                                @endif
                               
                            </div>
                        @endif
                    @else
                        NA
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

<div class="card" style="background-color: rgba(252, 226, 231, 0.3);">
    <div class="card-body small text-danger fw-semibold">
        <p class="mb-2 text-dark">IMPORTANT INFORMATION</p>
        <ul class="mb-0  ps-3">
            <li style="font-size:14px;"class="p-0 m-0 text-dark mt-1 ">- You should carry a print-out of your booking and present for check-in.</li>
            <li style="font-size:14px;"class="p-0 m-0 text-dark mt-1 ">- Date & Time is calculated based on the local time of city/destination.</li>
            <li style="font-size:14px;"class="p-0 m-0 text-dark mt-1 ">- Use the Reference Number for all Correspondence with us.</li>
            <li style="font-size:14px;"class="p-0 m-0 text-dark mt-1 ">- Use the Airline PNR for all Correspondence directly with the Airline.</li>
            <li style="font-size:14px;"class="p-0 m-0 text-dark mt-1 ">- For departure terminal please check with airline first.</li>
            <li style="font-size:14px;"class="p-0 m-0 text-dark mt-1 ">- Please Check-In at least 2 hours prior to the departure for domestic flights and 3 hours prior to the departure of international flights.</li>
            <li style="font-size:14px;"class="p-0 m-0 text-dark mt-1 ">- For rescheduling/cancellation within 4 hours of departure time contact the airline directly.</li>
        </ul>
    </div>
</div>

@endsection
