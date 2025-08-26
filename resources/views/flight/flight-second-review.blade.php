@extends('layout.layout')
<style>
    #expiryTimerBar {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: #000000;
        color: #ffffff;
        text-align: center;
        padding: 10px 0;
        z-index: 9999;
        font-size: 16px;
        font-weight: bold;
    }

    .expiry-content {
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>

@section('content')
@php
$sessionStartTime = $tripData['conditions']['sct'] ?? now()->toIso8601String();
$sessionExpirySeconds = $tripData['conditions']['st'] ?? 1800;
@endphp

@php
$steps = [
['label' => 'Flight Itinerary', 'icon' => 'fa-plane', 'step' => 1],
['label' => 'Passenger Details', 'icon' => 'fa-user', 'step' => 2],
['label' => 'Review', 'icon' => 'fa-file-alt', 'step' => 3],
['label' => 'Payments', 'icon' => 'fa-credit-card', 'step' => 4],
];
@endphp

{{-- Stepper --}}
<div class="bg-light py-3 border-bottom">
    <div class="container d-flex justify-content-between align-items-center">
        @foreach ($steps as $item)
        <div class="text-center flex-fill position-relative">
            <div class="rounded-circle mx-auto mb-1 d-flex align-items-center justify-content-center"
                style="width: 40px; height: 40px; background-color: {{ $currentStep == $item['step'] ? '#f37321' : '#e0e0e0' }};">
                <i class="fa {{ $item['icon'] }} text-white"></i>
            </div>
            <div class="small text-uppercase d-none d-sm-block"
                style="color: {{ $currentStep == $item['step'] ? '#f37321' : '#888' }}">
                <strong>{{ $item['step'] == 4 ? 'FINISH STEP' : 'STEP ' . $item['step'] }}</strong><br>
                <span class="d-none d-md-block">{{ $item['label'] }}</span>
            </div>
        </div>
        @endforeach
    </div>
</div>


{{-- Flight & Fare Details --}}
<div class="container my-4">
    <div class="row">
        {{-- Flight Details --}}
        <div class="col-xl-9">
            <h5 class="mb-3 fw-bold">Flight Details</h5>

            @foreach($tripData as $index => $trip)
            @php
            $flightList = $trip['sI'];
            $priceData = $trip['totalPriceList'][0]['fd'] ?? [];
            $fareIdentifier = $trip['totalPriceList'][0]['fareIdentifier'] ?? 'N/A';
            $priceId = $trip['totalPriceList'][0]['id'] ?? '';
            $firstSeg = $flightList[0];
            $lastSeg = end($flightList);
            $fromCity = $firstSeg['da']['city'] ?? '';
            $toCity = $lastSeg['aa']['city'] ?? '';
            $departureDateTime = \Carbon\Carbon::parse($firstSeg['dt'])->format('D, M jS Y');

            // Stopovers
            $stopovers = [];
            foreach ($flightList as $seg) {
            $arrivalCity = $seg['aa']['city'] ?? '';
            if (!in_array($arrivalCity, [$fromCity, $toCity])) {
            $stopovers[] = $arrivalCity;
            }
            $departureCity = $seg['da']['city'] ?? '';
            if (($seg['sN'] ?? 0) === 1 && !in_array($departureCity, [$fromCity, $toCity])) {
            $stopovers[] = $departureCity;
            }
            }

            $stopovers = array_unique($stopovers);
            $stopCount = count($stopovers);
            $stopLabel = $stopCount === 0 ? 'Non-stop' : $stopCount . ' stop' . ($stopCount > 1 ? 's' : '');
            $stopTooltip = $stopCount > 0 ? 'Via: ' . implode(', ', $stopovers) : '';
            @endphp

            <div class="bg-white shadow-sm rounded p-3 mb-4 border">
                {{-- Route Header --}}

                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3 bg-secondary bg-opacity-10 p-2 rounded">
                    <div class="d-flex">
                        <h6 class="fw-bold mb-0 me-2">{{ $fromCity }} → {{ $toCity }}</h6>
                        <small class="text-muted">{{ $departureDateTime }}</small>
                    </div>
                </div>

                {{-- Flight Segments --}}
                @foreach ($flightList as $i => $segData)
                @if ($i > 0)
                @php
                $prevArrival = \Carbon\Carbon::parse($flightList[$i - 1]['at']);
                $currDeparture = \Carbon\Carbon::parse($segData['dt']);
                $layoverDuration = $prevArrival->diffInMinutes($currDeparture);
                $layoverText = floor($layoverDuration / 60) . 'h ' . ($layoverDuration % 60) . 'm';
                $layoverCity = $flightList[$i - 1]['aa']['city'] ?? '';
                @endphp

                {{-- Layover Info --}}
                <div class="row mb-2 text-center">
                    <div class="col-12">
                        <span class="badge bg-light text-dark">
                            Change Plane at {{ $layoverCity }} – Layover Time: {{ $layoverText }}
                        </span>
                    </div>
                </div>
                @endif

                @php
                $airlineCode = $segData['fD']['aI']['code'] ?? 'XX';
                $flightNumber = $segData['fD']['fN'] ?? '';
                $logoPath = public_path("AirlinesLogo/$airlineCode.png");
                $logoUrl = file_exists($logoPath) ? asset("AirlinesLogo/$airlineCode.png") : asset("AirlinesLogo/default.png");

                $depTime = \Carbon\Carbon::parse($segData['dt'])->format('M d, D, H:i');
                $arrTime = \Carbon\Carbon::parse($segData['at'])->format('M d, D, H:i');

                $depCity = $segData['da']['city'] ?? '';
                $arrCity = $segData['aa']['city'] ?? '';
                $depAirportName = $segData['da']['name'] ?? '';
                $arrAirportName = $segData['aa']['name'] ?? '';

                $durationMin = abs(\Carbon\Carbon::parse($segData['at'])->diffInMinutes(\Carbon\Carbon::parse($segData['dt'])));
                $durationText = floor($durationMin / 60) . 'h ' . ($durationMin % 60) . 'm';

                $classCode = $priceData['ADULT']['cc'] ?? 'ECONOMY';
                $airlineName = $segData['fD']['aI']['name'] ?? 'Unknown Airline';
                $equipmentCode = $segData['fD']['eT'] ?? '';

                $isRefundable = $priceData['refundable'] ?? false;
                $refundableText = $isRefundable ? 'Refundable' : 'Non-Refundable';

                $cabinBaggage = $priceData['ADULT']['bI']['cB'] ?? 'N/A';
                $checkinBaggage = $priceData['ADULT']['bI']['iB'] ?? 'N/A';

                $depTerminal = $segData['da']['terminal'] ?? null;
                $arrTerminal = $segData['aa']['terminal'] ?? null;
                @endphp

                <div class="row mb-3">

                    <div class="col-12">
                        <div class="d-flex flex-column justify-content-start gap-3">
                            <div class="d-flex flex-column flex-lg-row justify-content-start justify-content-lg-between gap-3 gap-lg-4">
                                <div class="d-flex flex-row flex-lg-column gap-4 justify-content-between justify-content-lg-start">
                                    <div class="d-flex gap-0 flex-column">
                                        <img src="{{ $logoUrl }}" alt="{{ $airlineCode }}" class="img-fluid" style="max-height: 40px; max-width: 40px;">
                                        <p class="mb-0 fw-semibold text-dark" style="font-size: 14px;">{{ $airlineName }}</p>
                                        <small style="font-size: 11px; line-height: 1;">{{ $airlineCode }}-{{ $flightNumber }}-{{ $equipmentCode }}</small>
                                    </div>
                                    <div class="d-flex flex-column gap-2 align-items-end align-items-lg-start">
                                        <span class="badge bg-warning text-white text-uppercase" style="font-size: 11px; padding: 4px 8px 3px; border-radius: 4px; font-weight: 200; max-width: fit-content;">{{ ucfirst(strtolower($fareIdentifier)) }}</span>
                                        <small class="text-muted" style="font-size: 11px; line-height: 1;">
                                            <i class="fa fa-suitcase me-1" style="color:#000; padding:2px;"></i>
                                            (Adult) Check-in: {{ $checkinBaggage }},
                                            Cabin: {{ $cabinBaggage }}
                                        </small>
                                        <small class="text-muted" style="font-size: 11px; line-height: 1;">{{ $refundableText }}</small>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between gap-4">
                                    <div class="d-flex gap-0 flex-column align-items-start align-items-sm-end">
                                        <h5 class="my-1 fw-semibold" style="font-size: 14px;">{{ $depTime }}</h5>
                                        <small class="text-muted mt-1" style="font-size: 11px; line-height: 1;">{{ $depCity }}</small>
                                        <small class="text-muted mt-2" style="font-size: 11px; line-height: 1;">{{ $depAirportName }}</small>
                                        @if($depTerminal)<small class="text-muted mt-2" style="font-size: 11px; line-height: 1;">{{ $depTerminal }}</small>@endif
                                    </div>
                                    <div class="d-flex flex-column align-item-center text-center gap-0">
                                        <small class="text-muted mt-2 mb-1" style="font-size: 11px; line-height: 1;" title="{{ $stopTooltip }}">{{ $stopLabel }}</small>
                                        <div style="width: 90px; height: 2px; background-color: orange; position: relative; margin: 9px 0; display: flex; align-items: center; justify-content: end;">
                                            <span style="color: orange; font-size: 36px; margin-top: -6px; margin-right: -3px;">→</span>
                                        </div>
                                        <small class="text-muted mt-1" style="font-size: 11px; line-height: 1;">{{ ucfirst(strtolower($classCode)) }}</small>
                                        <small class="text-muted mt-1" style="font-size: 11px; line-height: 1;">{{ $durationText }}</small>
                                    </div>
                                    <div class="d-flex gap-0 flex-column align-items-start">
                                        <h5 class="my-1 fw-semibold" style="font-size: 14px;">{{ $arrTime }}</h5>
                                        <small class="text-muted mt-1" style="font-size: 11px; line-height: 1;">{{ $arrCity }}</small>
                                        <small class="text-muted mt-2" style="font-size: 11px; line-height: 1;">{{ $arrAirportName }}</small>
                                        @if($arrTerminal)<small class="text-muted mt-2" style="font-size: 11px; line-height: 1;">{{ $arrTerminal }}</small>@endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @endforeach


                {{-- Passenger Details --}}
                <div class="bg-white shadow-sm rounded p-3 mb-4 border">
                    <h6 class="fw-bold mb-3">Passenger Details ({{ count($passengerDetails) }})</h6>

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
                            @php
                            // passenger values
                            $baggage = $passenger['baggage'] ?? null;
                            $meal = $passenger['meal'] ?? null;

                            // flight city codes
                            $firstTrip = $tripData[0] ?? null;
                            $flightList = $firstTrip['sI'] ?? [];
                            $firstFlight = $flightList[0] ?? null;
                            $lastFlight = end($flightList);

                            $depcity = $firstFlight['da']['code'] ?? 'NA';
                            $arrcity = $lastFlight['aa']['code'] ?? 'NA';
                            @endphp

                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    {{ strtoupper($passenger['title'] ?? '') }}
                                    {{ strtoupper($passenger['first_name'] ?? '') }}
                                    {{ strtoupper($passenger['last_name'] ?? '') }}

                                    @if (!empty($passenger['dob']))
                                    <br><small>{{ \Carbon\Carbon::parse($passenger['dob'])->format('d/m/Y') }}</small>
                                    @endif
                                </td>
                                <td>NA</td>
                                <td class="text-start">
                                    {{-- Baggage --}}
                                    @if($baggage)
                                    <i class="fas fa-suitcase-rolling me-1"></i>
                                    {{ $depcity }} → {{ $arrcity }} + {{ $baggage }}Kg Xcess Baggage
                                    @else
                                    <span class="text-muted">No extra baggage</span>
                                    @endif

                                    {{-- Meal --}}
                                    @if($meal)
                                    <br><i class="fas fa-utensils me-1"></i> Meal: {{ $meal }}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Contact Details --}}
                    <div class="mt-3">
                        <h6 class="fw-bold">Contact Details</h6>
                        <p class="mb-1">
                            Email:
                            @if(!empty($contactDetails['email']))
                            <a href="mailto:{{ $contactDetails['email'] }}">{{ $contactDetails['email'] }}</a>
                            @else
                            NA
                            @endif
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


                {{-- ✅ Add content BELOW the bordered box --}}
                <div class="alert alert-info mt-3">
                    <strong>Note:</strong> Please double-check passenger details before proceeding to payment.
                </div>

                {{-- You can also include buttons, summaries, or next steps here --}}


            </div>
            @endforeach
            {{-- Proceed button --}}


            @php
            $priceId = $trip['totalPriceList'][0]['id'] ?? '';
            $totalFare = $tf;
            @endphp

            <input type="hidden" id="priceId_{{ $index }}" value="{{ $priceId }}">
            <input type="hidden" id="initialPrice_{{ $index }}" value="{{ $totalFare }}">

            <div class="d-flex justify-content-between mt-3">
                <button class="btn btn-border-none text-white" style="background-color: #f37321;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chevrons-left">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M11 7l-5 5l5 5" />
                        <path d="M17 7l-5 5l5 5" />
                    </svg>
                    <span>Back</span>
                </button>

                <div class="d-flex">
                    <a href="" class="btn btn-border-none me-2" style="background-color: #f37321; color:white;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-ban">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                            <path d="M5.7 5.7l12.6 12.6" />
                        </svg>
                        <span>Block</span>
                    </a>

                    <a href="{{route('payment')}}" class="btn btn-border-none" style="background-color: #f37321; color:white;">
                        <span> Proceed to Payment</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chevrons-right">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M7 7l5 5l-5 5" />
                            <path d="M13 7l5 5l-5 5" />
                        </svg>
                    </a>
                </div>

                <button id="loadingBtn_{{ $index }}" class="btn btn-border-none text-white d-none" style="background-color: #f37321;" disabled>
                    <span class="spinner-border spinner-border-sm me-2"></span> Confirming Price...
                </button>

            </div>


        </div>


        {{-- Fare Summary --}}
        <div class="col-md-3">
            <h6 class="fw-bold mb-3">Fare Summary</h6>
            @php
            $fareType = $trip['totalPriceList'][0]['fareIdentifier'] ?? 'N/A';
            $priceId = $trip['totalPriceList'][0]['id'] ?? 'N/A';

            $adultFareData = $trip['totalPriceList'][0]['fd']['ADULT'] ?? [];
            $childFareData = $trip['totalPriceList'][0]['fd']['CHILD'] ?? [];
            $fC = $adultFareData['fC'] ?? [];
            $child_fc = $childFareData['fC'] ?? [];
            $afC_TAF = $adultFareData['afC']['TAF'] ?? [];
            $afC_NCM = $adultFareData['afC']['NCM'] ?? [];

            // Fare Components
            $baseFare = $fC['BF'] ?? 0;
            $child_fare = $child_fc['BF'] ?? 0;
            $taxAndFee = $fC['TAF'] ?? 0;
            $child_fee = $child_fc['TAF'] ?? 0;

            // Tax Breakdown
            $airlineGst = $afC_TAF['AGST'] ?? 0;
            $mgmtFee = $afC_TAF['MF'] ?? 0;
            $mgmtFeeTax = $afC_TAF['MFT'] ?? 0;
            $otherTaxes = $afC_TAF['OT'] ?? 0;
            $yrTax = $afC_TAF['YR'] ?? 0;
            $ftcTax = $afC_TAF['FTC'] ?? 0;

            // Base amount
            $amountToPay = $tf;
            $commission = $trip['totalPriceList'][0]['commission'] ?? 0;
            $tds = $afC_NCM['TDS'] ?? 0;

            // ✅ Calculate baggage total from passengerDetails
            $baggageTotal = 0;
            foreach ($passengerDetails as $p) {
            if (!empty($p['baggage_amount'])) {
            $baggageTotal += (float) $p['baggage_amount'];
            }
            }

            // No meal in your current payload, so keep 0
            $mealTotal = 0;

            $extrasTotal = $baggageTotal + $mealTotal;

            // ✅ Net Price including baggage + meal
            $netPrice = $amountToPay - $commission + $tds + $extrasTotal;
            @endphp
            <div class="bg-white shadow-sm rounded mb-4 border p-3">

                <ul class="list-unstyled small mb-2">
                    <li class="text-muted d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><strong class="my-1 fw-semibold" style="font-size: 14px;">Base Fare:</strong> <span>₹{{ number_format($baseFare + $child_fare, 2) }}</span></li>
                    <li>
                        <a class="text-dark text-decoration-none" data-bs-toggle="collapse" href="#taxBreakdown" role="button">
                            <strong>Taxes & Fees:</strong> ₹{{ number_format($taxAndFee + $child_fee, 2) }}
                            <i class="fa fa-chevron-down float-end"></i>
                        </a>
                        <div class="collapse mt-2" id="taxBreakdown">
                            <ul class="list-unstyled ms-3">
                                @if($yrTax) <li class="text-muted mb-2 d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><span>YR Tax:</span> <span>₹{{ number_format($yrTax, 2) }}</span></li> @endif
                                @if($otherTaxes) <li class="text-muted mb-2 d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><span>Other Taxes:</span> <span>₹{{ number_format($otherTaxes, 2) }}</span></li> @endif
                                @if($airlineGst) <li class="text-muted mb-2 d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><span>Airline GST:</span> <span>₹{{ number_format($airlineGst, 2) }}</span></li> @endif
                                @if($ftcTax) <li class="text-muted mb-2 d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><span>FTC:</span> <span>₹{{ number_format($ftcTax, 2) }}</span></li> @endif
                                @if($mgmtFee) <li class="text-muted mb-2 d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><span>Management Fee:</span> <span>₹{{ number_format($mgmtFee, 2) }}</span></li> @endif
                                @if($mgmtFeeTax) <li class="text-muted mb-2 d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><span>Mgmt Fee Tax:</span> <span>₹{{ number_format($mgmtFeeTax, 2) }}</span></li> @endif
                            </ul>
                        </div>
                    </li>
                </ul>

                <ul class="list-unstyled small mb-2">
                    @if($baggageTotal)
                    <li class="text-muted d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><strong class="my-1 fw-semibold" style="font-size: 14px;">Baggage Charges:</strong> <span>₹{{ number_format($baggageTotal, 2) }}</span></li>
                    @endif
                    @if($mealTotal)
                    <li class="text-muted d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><strong class="my-1 fw-semibold" style="font-size: 14px;">Meal Charges:</strong> <span>₹{{ number_format($mealTotal, 2) }}</span></li>
                    @endif
                </ul>

                <div style="border-bottom:1px solid #f1f1f1; margin:10px;"></div>

                <ul class="list-unstyled small mb-2">
                    <li class="text-muted d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><strong class="my-1 fw-semibold" style="font-size: 14px;">Total Amount:</strong> <span>₹{{ number_format($amountToPay, 2) }}</span></li>

                    <li class="mt-2">
                        <a class="text-dark text-decoration-none" data-bs-toggle="collapse" href="#amountBreakdown" role="button">
                            <strong>Amount Breakdown</strong>
                            <i class="fa fa-chevron-down float-end"></i>
                        </a>
                        <div class="collapse mt-2" id="amountBreakdown">
                            <ul class="list-unstyled ms-3">
                                <li class="text-muted mb-2 d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><span>Commission:</span> <span>-₹{{ number_format($commission, 2) }}</span></li>
                                <li class="text-muted mb-2 d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><span>TDS:</span> <span>+₹{{ number_format($tds, 2) }}</span></li>
                                <li class="text-muted mb-2 d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><strong class="my-1 fw-semibold" style="font-size: 14px;">Net Price: </strong><strong class="my-1 fw-semibold" style="font-size: 14px;"> ₹{{ number_format($netPrice, 2) }}</strong></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>


        </div>
    </div>

    {{-- timer bar --}}
    <div id="expiryTimerBar" class="d-none">
        <div class="expiry-content">
            <i class="fa fa-clock me-2"></i>
            <span id="expiryTimerMessage">
                Your Session will expire in
                <span id="timerMinutes">14</span> mins :
                <span id="timerSeconds">59</span> secs
            </span>
        </div>
    </div>



    {{-- modal --}}
    <div class="modal fade" id="sessionExpiryModal" tabindex="-1" aria-labelledby="sessionExpiryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sessionExpiryModalLabel">Session Expired</h5>
                </div>
                <div class="modal-body">
                    <!-- Dynamic message will come here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='{{route('tripjack.search')}}'">Back to flight list</button>
                    <button type="button" class="btn btn-primary" onclick="window.location.reload();">Continue</button>
                </div>
            </div>
        </div>
    </div>

    {{-- pricemodal --}}
    <div id="priceAlertModal" class="d-none">
        <div class="alert-content">
            <span id="priceAlertMessage"></span>
        </div>
    </div>

    @endsection

    <script>
        function showFareTab(indexId, ruleType) {
            const tableRows = document.querySelectorAll(`#${indexId}_tab_content .fare-tab-row`);
            const types = ['CANCELLATION', 'DATECHANGE', 'NO_SHOW', 'SEAT_CHARGEABLE'];

            // Hide all content cells first
            types.forEach(type => {
                document.querySelectorAll(`#${indexId}_tab_content .fare-content-${type.toLowerCase()}`).forEach(cell => {
                    cell.classList.add('d-none');
                });
            });

            // Loop through each row and only show rows which have data for selected ruleType
            tableRows.forEach(row => {
                const dataAttr = 'data-' + ruleType.toLowerCase();
                const hasValue = row.getAttribute(dataAttr);
                if (hasValue === '1') {
                    row.classList.remove('d-none');
                    row.querySelector(`.fare-content-${ruleType.toLowerCase()}`).classList.remove('d-none');
                } else {
                    row.classList.add('d-none');
                }
            });
        }

        function toggleDetails(indexId, btn) {
            const container = document.getElementById(indexId + '_tab_content');
            container.classList.toggle('d-none');
            if (!container.classList.contains('d-none')) {
                showFareTab(indexId, 'CANCELLATION'); // Default to Cancellation Tab
                btn.innerText = 'Fare Rules -';
            } else {
                btn.innerText = 'Fare Rules +';
            }
        }
    </script>

    <script>
        const SESSION_START_TIME = "{{ $sessionStartTime }}"; // "2025-08-05T15:54:39.486"
        const SESSION_EXPIRY_SECONDS = {
            {
                $sessionExpirySeconds
            }
        };
        // Example: 1800
    </script>


    <script>
        window.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[id^="proceedBtn_"]').forEach(btn => {
                const index = btn.id.split('_')[1];
                autoConfirmPrice(index);

                // Start expiry timer dynamically
                startExpiryTimer(SESSION_EXPIRY_SECONDS, 0);
            });
        });

        function autoConfirmPrice(index) {
            const priceId = document.getElementById('priceId_' + index).value;
            const initialPrice = parseFloat(document.getElementById('initialPrice_' + index).value);

            // Hide Proceed, Show Loading
            document.getElementById('proceedBtn_' + index).classList.add('d-none');
            document.getElementById('loadingBtn_' + index).classList.remove('d-none');

            fetch('/api/flight/review-price', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        priceId: priceId,
                        initialPrice: initialPrice
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'same') {
                        // Price is same, show Proceed button
                        document.getElementById('loadingBtn_' + index).classList.add('d-none');
                        document.getElementById('proceedBtn_' + index).classList.remove('d-none');
                    } else if (data.status === 'updated') {
                        alert('The flight price has been updated to ₹' + data.newPrice + '. Please proceed.');
                        document.getElementById('initialPrice_' + index).value = data.newPrice;
                        document.getElementById('loadingBtn_' + index).classList.add('d-none');
                        document.getElementById('proceedBtn_' + index).classList.remove('d-none');
                    } else {

                        document.getElementById('loadingBtn_' + index).classList.add('d-none');
                        document.getElementById('proceedBtn_' + index).classList.remove('d-none');
                    }
                })
                .catch(error => {
                    console.error('Price verification failed:', error);
                    document.getElementById('loadingBtn_' + index).classList.add('d-none');
                    document.getElementById('proceedBtn_' + index).classList.remove('d-none');
                });
        }

        function confirmPrice(index) {
            // You can reuse autoConfirmPrice for manual button click also
            autoConfirmPrice(index);
        }
    </script>
    <script>
        let expiryTimerIntervalId = null; // Global to keep track of interval

        function startExpiryTimer(totalSeconds, index) {
            const expiryBar = document.getElementById('expiryTimerBar');
            const minutesSpan = document.getElementById('timerMinutes');
            const secondsSpan = document.getElementById('timerSeconds');

            if (!expiryBar || !minutesSpan || !secondsSpan) return;

            expiryBar.classList.remove('d-none');

            let remainingSeconds = totalSeconds;

            // Immediately update UI before interval starts ticking
            updateTimerUI(remainingSeconds, minutesSpan, secondsSpan);

            // Clear previous interval if any (prevent multiple intervals)
            if (expiryTimerIntervalId) clearInterval(expiryTimerIntervalId);

            expiryTimerIntervalId = setInterval(() => {
                remainingSeconds--;

                if (remainingSeconds < 0) {
                    // console.log('working');
                    clearInterval(expiryTimerIntervalId); // Stop the Timer

                    expiryBar.classList.add('d-none'); // Hide Timer Bar
                    document.getElementById('proceedBtn_' + index)?.setAttribute('disabled', true); // Disable Proceed Button

                    showSessionExpiryModal(); // Show Expiry Modal
                    return;
                }

                updateTimerUI(remainingSeconds, minutesSpan, secondsSpan);
            }, 1000);
        }

        function updateTimerUI(remainingSeconds, minutesSpan, secondsSpan) {
            const minutes = Math.floor(remainingSeconds / 60);
            const seconds = remainingSeconds % 60;

            minutesSpan.innerText = minutes;
            secondsSpan.innerText = seconds.toString().padStart(2, '0');
        }

        function getElapsedMinutes(sessionStart) {
            const startTime = new Date(sessionStart).getTime();
            const currentTime = new Date().getTime();
            const diffInMinutes = Math.floor((currentTime - startTime) / 60000);
            return diffInMinutes;
        }

        function showSessionExpiryModal() {
            const elapsedMinutes = getElapsedMinutes(SESSION_START_TIME);
            document.querySelector('#sessionExpiryModal .modal-body').innerHTML =
                `It has been over <strong>${elapsedMinutes} minutes</strong> since the price was last updated. Click on Continue to view the latest price and availability.`;

            const modal = new bootstrap.Modal(document.getElementById('sessionExpiryModal'));
            modal.show();
        }
    </script>