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
                <div class="small text-uppercase"
                     style="color: {{ $currentStep == $item['step'] ? '#f37321' : '#888' }}">
                    <strong>{{ $item['step'] == 4 ? 'FINISH STEP' : 'STEP ' . $item['step'] }}</strong><br>
                    <span>{{ $item['label'] }}</span>
                </div>
            </div>
        @endforeach
    </div>
</div>


{{-- Flight & Fare Details --}}
<div class="container my-4">
    <div class="row">
        {{-- Flight Details --}}
      <div class="col-md-9">
    <h5 class="mb-4 fw-bold">Flight Details</h5>
 <div class="bg-white shadow-sm rounded p-3 mb-4 border">
    @foreach($tripData as $tripIndex => $trip)
       @php
        $flightList = $trip['sI'];
        $priceData = $trip['totalPriceList'][0]['fd'] ?? [];
        $fareIdentifier = $trip['totalPriceList'][0]['fareIdentifier'] ?? 'N/A';

        $firstSeg = $flightList[0];
        $lastSeg = end($flightList);
        $fromCity = $firstSeg['da']['city'] ?? '';
        $toCity = $lastSeg['aa']['city'] ?? '';
        $departureDateTime = \Carbon\Carbon::parse($firstSeg['dt'])->format('D, M jS Y');

        // Identify trip type (Outbound, Return, MultiCity leg)
        $legType = $tripIndex == 0 ? 'Outbound' : ($tripIndex == 1 ? 'Return' : 'Leg '.($tripIndex+1));

        $depTime = \Carbon\Carbon::parse($firstSeg['dt'])->format('M d, D, H:i');
        $arrTime = \Carbon\Carbon::parse($lastSeg['at'])->format('M d, D, H:i');

        $durationMin = abs(\Carbon\Carbon::parse($lastSeg['at'])->diffInMinutes(\Carbon\Carbon::parse($firstSeg['dt'])));
        $durationText = floor($durationMin / 60) . 'h ' . ($durationMin % 60) . 'm';

        // 🔹 Stopover logic
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

       
           <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3 bg-secondary bg-opacity-10 p-2 rounded">
    <div class="d-flex">
        <h6 class="fw-bold mb-0 me-2">{{ $fromCity }} → {{ $toCity }}</h6>
        <small class="text-muted">{{ $departureDateTime }}</small>
    </div>
    <span class="">
        <i class="fa fa-clock-o me-1"></i> {{ $durationText }}
    </span>
</div>
        {{-- Segments --}}
        @foreach($flightList as $i => $segData)
            @php
                $airlineCode = $segData['fD']['aI']['code'] ?? 'XX';
                $airlineName = $segData['fD']['aI']['name'] ?? 'Unknown Airline';
                $flightNumber = $segData['fD']['fN'] ?? '';
                $equipmentCode = $segData['fD']['eT'] ?? '';

                $depTime = \Carbon\Carbon::parse($segData['dt'])->format('M d, D, H:i');
                $arrTime = \Carbon\Carbon::parse($segData['at'])->format('M d, D, H:i');

                $depCity = $segData['da']['city'] ?? '';
                $arrCity = $segData['aa']['city'] ?? '';
                $depAirport = $segData['da']['name'] ?? '';
                $arrAirport = $segData['aa']['name'] ?? '';
                $depTerminal = $segData['da']['terminal'] ?? null;
                $arrTerminal = $segData['aa']['terminal'] ?? null;

                $durationMin = abs(\Carbon\Carbon::parse($segData['at'])->diffInMinutes(\Carbon\Carbon::parse($segData['dt'])));
                $durationText = floor($durationMin / 60) . 'h ' . ($durationMin % 60) . 'm';

                $classCode = $priceData['ADULT']['cc'] ?? 'ECONOMY';
                $cabinBaggage = $priceData['ADULT']['bI']['cB'] ?? 'N/A';
                $checkinBaggage = $priceData['ADULT']['bI']['iB'] ?? 'N/A';
            @endphp

            {{-- Layover Info --}}
            @if($i > 0)
                @php
                    $prevArrival = \Carbon\Carbon::parse($flightList[$i - 1]['at']);
                    $currDeparture = \Carbon\Carbon::parse($segData['dt']);
                    $layoverDuration = $prevArrival->diffInMinutes($currDeparture);
                    $layoverText = floor($layoverDuration / 60) . 'h ' . ($layoverDuration % 60) . 'm';
                    $layoverCity = $flightList[$i - 1]['aa']['city'] ?? '';
                @endphp
                <div class="row mb-2 text-center">
                    <div class="col-12">
                        <span class="badge bg-light text-dark">
                            Change Plane at {{ $layoverCity }} – Layover: {{ $layoverText }}
                        </span>
                    </div>
                </div>
            @endif

            {{-- Flight Segment --}}
            <div class="row align-items-center mb-3">
                <div class="col-md-2 d-flex align-items-center">
                    <img src="{{ asset("AirlinesLogo/$airlineCode.png") }}" onerror="this.src='{{ asset("AirlinesLogo/default.png") }}'" style="height:24px;" class="me-2">
                    <div>
                        <div class="fw-bold small">{{ $airlineName }}</div>
                        <div class="text-muted small">{{ $airlineCode }}-{{ $flightNumber }}</div>
                        <span class="text-muted small">-{{ $equipmentCode }}</span>
                    </div>
                </div>

                <div class="col-md-3 text-center">
                    <div class="fw-bold">{{ $depTime }}</div>
                    <small>{{ $depCity }}</small><br>
                    <small class="text-muted">{{ $depAirport }}</small>
                    @if($depTerminal)<br><span class="text-muted">{{ $depTerminal }}</span>@endif
                </div>

                {{-- 🔹 Stopover Column --}}
                <div class="col-md-2 text-center">
                    <small class="text-muted" title="{{ $stopTooltip }}">{{ $stopLabel }}</small>
                    <div style="width: 100%; height: 2px; background-color: #f37321; margin: 5px 0; position: relative;">
                        <span style="position: absolute; right: -8px; top: -6px; color: #f37321;">→</span>
                    </div>
                </div>

                <div class="col-md-3 text-center">
                    <div class="fw-bold">{{ $arrTime }}</div>
                    <small>{{ $arrCity }}</small><br>
                    <small class="text-muted">{{ $arrAirport }}</small>
                    @if($arrTerminal)<br><span class="text-muted">{{ $arrTerminal }}</span>@endif
                </div>

                <div class="col-md-2 text-center">
                    <small class="text-muted">{{ $durationText }}</small>
                    <small class="d-block text-muted">{{ ucfirst(strtolower($classCode)) }}</small>
                </div>
            </div>

           <div class="col-md-2">
    <span class="badge bg-warning bg-opacity-50 text-dark">
        {{ ucfirst(strtolower($fareIdentifier)) }}
    </span>
</div>

            <div class="text-center">
    {{-- Baggage Info --}}
    <small class="d-flex text-muted mt-2">
        <i class="fa fa-suitcase me-1" style="color:#000; padding:2px;"></i>
        (Adult) Check-in: {{ $checkinBaggage }},
        Cabin: {{ $cabinBaggage }}
    </small>
</div>

<div style="border-bottom:1px solid #f1f1f1; margin:10px;"></div>
        @endforeach

       
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
                    <td>
                        @php
                            $firstTrip = $tripData[0] ?? null;
                            $flightList = $firstTrip['sI'] ?? [];
                            $firstFlight = $flightList[0] ?? null;
                            $lastFlight = end($flightList);
                            $depcity = $firstFlight['da']['code'] ?? 'NA';
                            $arrcity = $lastFlight['aa']['code'] ?? 'NA';
                        @endphp

                        <i class="fas fa-suitcase-rolling me-1"></i> {{ $depcity }} → {{ $arrcity }}<br>

                        @foreach ($passenger['flights'] ?? [] as $flightIndex => $flight)
                            <small>
                                Baggage: {{ $flight['baggage'] ?? 'NA' }}<br>
                                Meal: {{ $flight['meal'] ?? 'NA' }}
                            </small>
                            @if (!$loop->last)
                            
                            <hr class="my-1">
                         <i class="fas fa-suitcase-rolling me-1"></i> {{ $arrcity }} → {{  $depcity }}<br>
                            @endif
                        @endforeach

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Contact Details --}}
    <div class="mt-3">
        <h6 class="fw-bold">Contact Details</h6>
        <p class="mb-1">Email: <a href="mailto:{{ $contactDetails['email'] ?? 'NA' }}">{{ $contactDetails['email'] ?? 'NA' }}</a></p>
        <p>Mobile: {{ $contactDetails['mobile'] ?? 'NA' }}</p>
    </div>

    <div class="form-check mt-2">
        <input class="form-check-input" type="checkbox" required id="agreeTerms">
        <label class="form-check-label small" for="agreeTerms">
            By proceeding, I acknowledge and agree to the <a href="#">terms & conditions</a>.
        </label>
    </div>
</div>


{{-- ✅ Add content BELOW the bordered box --}}
<div class="alert alert-info mt-3">
    <strong>Note:</strong> Please double-check passenger details before proceeding to payment.
</div>
     {{-- Proceed button --}}
 </div>

     @php
    $priceId = $trip['totalPriceList'][0]['id'] ?? '';
    $totalFare = $tf;
@endphp

<input type="hidden" id="priceId_{{ $tripIndex }}" value="{{ $priceId }}">
<input type="hidden" id="initialPrice_{{ $tripIndex }}" value="{{ $totalFare }}">

    <div class="d-flex justify-content-between mt-3">
    <button class="btn btn-border-none text-white" style="background-color: #f37321;">Back</button>
    
<div class="d-flex">
     <a href="" class="btn btn-border-none me-2" style="background-color: #f37321; color:white;">
        Block
    </a>

    <a href="{{route('payment_round')}}" class="btn btn-border-none" style="background-color: #f37321; color:white;">
        Proceed to Payment
    </a>
</div>

<button id="loadingBtn_{{$tripIndex}}" class="btn btn-border-none text-white d-none" style="background-color: #f37321;" disabled>
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
         $child_fc =  $childFareData['fC'] ?? [];
        $afC_TAF = $adultFareData['afC']['TAF'] ?? [];
        $afC_NCM = $adultFareData['afC']['NCM'] ?? [];

        // Fare Components
        $baseFare = $fC['BF'] ?? 0;
       $child_fare = $fC['BF'] ?? 0;
        $taxAndFee = $fC['TAF'] ?? 0;
         $child_fee = $fC['TAF'] ?? 0;
        // Tax Breakdown
        $airlineGst = $afC_TAF['AGST'] ?? 0;
        $mgmtFee = $afC_TAF['MF'] ?? 0;
        $mgmtFeeTax = $afC_TAF['MFT'] ?? 0;
        $otherTaxes = $afC_TAF['OT'] ?? 0;
        $yrTax = $afC_TAF['YR'] ?? 0;
        $ftcTax = $afC_TAF['FTC'] ?? 0;

        // Amounts
        $amountToPay = $tf;
        $commission = $trip['totalPriceList'][0]['commission'] ?? 0;
        $tds = $afC_NCM['TDS'] ?? 0;
        $netPrice = $amountToPay - $commission + $tds;
    @endphp

    <div class="bg-white shadow-sm rounded mb-4 border p-3">
      

        <ul class="list-unstyled small mb-2">
            <li><strong>Base Fare:</strong> ₹{{ number_format($baseFare + $child_fare, 2) }}</li>
            <li>
                <a class="text-dark text-decoration-none" data-bs-toggle="collapse" href="#taxBreakdown" role="button">
                    <strong>Taxes & Fees:</strong> ₹{{ number_format($taxAndFee + $child_fee, 2) }}
                    <i class="fa fa-chevron-down float-end"></i>
                </a>
                <div class="collapse mt-2" id="taxBreakdown">
                    <ul class="list-unstyled ms-3">
                        @if($yrTax) <li>YR Tax: ₹{{ number_format($yrTax, 2) }}</li> @endif
                        @if($otherTaxes) <li>Other Taxes: ₹{{ number_format($otherTaxes, 2) }}</li> @endif
                        @if($airlineGst) <li>Airline GST: ₹{{ number_format($airlineGst, 2) }}</li> @endif
                        @if($ftcTax) <li>FTC: ₹{{ number_format($ftcTax, 2) }}</li> @endif
                        @if($mgmtFee) <li>Management Fee: ₹{{ number_format($mgmtFee, 2) }}</li> @endif
                        @if($mgmtFeeTax) <li>Mgmt Fee Tax: ₹{{ number_format($mgmtFeeTax, 2) }}</li> @endif
                    </ul>
                </div>
            </li>
        </ul>

        <hr>

        <ul class="list-unstyled small mb-2">
            <li><strong>Total Amount:</strong> ₹{{ number_format($amountToPay, 2) }}</li>

            <li class="mt-2">
                <a class="text-dark text-decoration-none" data-bs-toggle="collapse" href="#amountBreakdown" role="button">
                    <strong>Amount Breakdown</strong>
                    <i class="fa fa-chevron-down float-end"></i>
                </a>
                <div class="collapse mt-2" id="amountBreakdown">
                    <ul class="list-unstyled ms-3">
                        <li>Commission: -₹{{ number_format($commission, 2) }}</li>
                        <li>TDS: +₹{{ number_format($tds, 2) }}</li>
                        <li><strong>Net Price: ₹{{ number_format($netPrice, 2) }}</strong></li>
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
            showFareTab(indexId, 'CANCELLATION');  // Default to Cancellation Tab
            btn.innerText = 'Fare Rules -';
        } else {
            btn.innerText = 'Fare Rules +';
        }
    }
</script>

<script>
    const SESSION_START_TIME = "{{ $sessionStartTime }}"; // "2025-08-05T15:54:39.486"
    const SESSION_EXPIRY_SECONDS = {{ $sessionExpirySeconds }}; 
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
    let expiryTimerIntervalId = null;  // Global to keep track of interval

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
                clearInterval(expiryTimerIntervalId);  // Stop the Timer

                expiryBar.classList.add('d-none');     // Hide Timer Bar
                document.getElementById('proceedBtn_' + index)?.setAttribute('disabled', true);  // Disable Proceed Button

                showSessionExpiryModal();  // Show Expiry Modal
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



