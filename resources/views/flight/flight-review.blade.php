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

    @foreach($tripData as $index => $trip)
        @php
            $flightList = $trip['sI'];
            $priceData = $trip['totalPriceList'][0]['fd'] ?? [];
            $fareIdentifier = $trip['totalPriceList'][0]['fareIdentifier'] ?? 'N/A';
            $priceId = $priceId1;
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
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                <div class="d-flex">
                    <h6 class="fw-bold mb-0">{{ $fromCity }} → {{ $toCity }}</h6>
                    <p class="mx-2">on</p>
                    <small class="text-muted mt-1">{{ $departureDateTime }}</small>
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
                        <div class="col-12" >
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

                <div class="row align-items-center mb-3">
                    {{-- Airline Info --}}
                    <div class="col-md-2 d-flex align-items-center">
                        <img src="{{ $logoUrl }}" alt="{{ $airlineCode }}" style="height: 24px;" class="me-2">
                        <div>
                            <div class="text-muted small">{{ $airlineName }}</div>
                            <div class="fw-bold small">{{ $airlineCode }}-{{ $flightNumber }}</div>
                            <span class="text-muted small">
                                <i class="fa fa-plane me-1" style="transform: rotate(-45deg); display: inline-block;"></i>
                                -{{ $equipmentCode }}
                            </span>
                        </div>
                    </div>

                    {{-- Departure --}}
                    <div class="col-md-3 text-center">
                        <div class="fw-bold">{{ $depTime }}</div>
                        <small>{{ $depCity }}</small><br>
                        <small class="text-muted">{{ $depAirportName }}</small>
                        @if ($depTerminal)
                            <br><span class="text-muted">{{ $depTerminal }}</span>
                        @endif
                    </div>

                    {{-- Stopover Info --}}
                    <div class="col-md-2 text-center">
                        <small class="text-muted" title="{{ $stopTooltip }}">{{ $stopLabel }}</small>
                        <div style="width: 100%; height: 2px; background-color: #f37321; margin: 5px 0; position: relative;">
                            <span style="position: absolute; right: -8px; top: -6px; color: #f37321;">→</span>
                        </div>
                    </div>

                    {{-- Arrival --}}
                    <div class="col-md-3 text-center">
                        <div class="fw-bold">{{ $arrTime }}</div>
                        <small>{{ $arrCity }}</small><br>
                        <small class="text-muted">{{ $arrAirportName }}</small>
                        @if ($arrTerminal)
                            <br><span class="text-muted">{{ $arrTerminal }}</span>
                        @endif
                    </div>

                    {{-- Misc Info --}}
                    <div class="col-md-2 text-center">
                        <small class="text-muted d-block">{{ $durationText }}</small>
                        <small class="text-muted d-block">{{ ucfirst(strtolower($classCode)) }}</small>
                        <small class="text-muted d-block">{{ $refundableText }}</small>
                    </div>
                </div>

                {{-- Fare & Baggage --}}
                <div class="border-top pt-2 mb-1">
                    <small class="text-muted p-1" style="background-color: rgb(253, 185, 57);">
                        {{ ucfirst(strtolower($fareIdentifier)) }}
                    </small>
                </div>
                <div class="pt-1 mb-3">
                    <small class="text-muted">
                        (Adult),
                        Cabin: {{ $cabinBaggage }}{{ is_numeric($cabinBaggage) ? ' Kg' : '' }},
                        Check-in: {{ $checkinBaggage }}{{ is_numeric($checkinBaggage) ? ' Kg' : '' }}
                    </small>
                </div>
            @endforeach

             {{-- fare  detail --}}
@php
    $fareRules = $trip['totalPriceList'][0]['fareRuleInformation']['tfr'] ?? [];

    $timeFrameRows = [];

    foreach (['CANCELLATION', 'DATECHANGE', 'NO_SHOW', 'SEAT_CHARGEABLE'] as $ruleType) {
        if (!empty($fareRules[$ruleType])) {
            foreach ($fareRules[$ruleType] as $rule) {
                $start = $rule['st'] ?? 0;
                $end = $rule['et'] ?? 0;
                $startFrame = ($start < 24) ? "{$start} hrs" : intval($start / 24) . " days";
                $endFrame = ($end < 24) ? "{$end} hrs" : intval($end / 24) . " days";
                $timeFrameKey = $startFrame . ' to ' . $endFrame;

                if (!isset($timeFrameRows[$timeFrameKey])) {
                    $timeFrameRows[$timeFrameKey] = [
                        'CANCELLATION' => null,
                        'DATECHANGE' => null,
                        'NO_SHOW' => null,
                        'SEAT_CHARGEABLE' => null
                    ];
                }

                if ($ruleType == 'CANCELLATION') {
                    $amount = $rule['fcs']['ACF'] ?? '0.00';
                    $fee = $rule['additionalFee'] ?? '0.00';
                    $timeFrameRows[$timeFrameKey]['CANCELLATION'] = '₹' . $amount . ' + ₹' . $fee;
                } elseif ($ruleType == 'DATECHANGE') {
                    $amount = $rule['amount'] ?? '0.00';
                    $fee = $rule['additionalFee'] ?? '0.00';
                    $timeFrameRows[$timeFrameKey]['DATECHANGE'] = '₹' . $amount . ' + ₹' . $fee . ' + Difference in Fare + Taxes';
                } elseif ($ruleType == 'NO_SHOW') {
                    $timeFrameRows[$timeFrameKey]['NO_SHOW'] = $rule['policyInfo'] ?? null;
                } elseif ($ruleType == 'SEAT_CHARGEABLE') {
                    $timeFrameRows[$timeFrameKey]['SEAT_CHARGEABLE'] = 'Paid Seat';
                }
            }
        }
    }

    $flightList = $trip['sI'];
    $firstFlight = $flightList[0];
    $lastFlight = end($flightList);
    $depcity = $firstFlight['da']['code'] ?? [];
    $arrcity = $lastFlight['aa']['code'] ?? [];
@endphp

<div class="mt-3">
    <button class="btn btn-link text-decoration-none fw-bold text-primary px-0" onclick="toggleDetails('{{ 'fare_toggle_' . $index }}', this)">
        Fare Rules +
    </button>

    @php $indexId = 'fare_toggle_' . $index; @endphp

    <div id="{{ $indexId }}_tab_content" class="d-none" style="background-color:#eeecec;">
        <div class="d-flex justify-content-between p-2">
            <small class="text-danger bg-light p-2" style="width:90px; height:40px; border-bottom: 1px solid #f37321;">
                {{ ucfirst($depcity) }} → {{ ucfirst($arrcity) }}
            </small>
            <button type="submit" class="p-2 bg-light btn btn-border-none border-bottom:1px solid #f37321;">
                Detailed Rule
            </button>
        </div>

        <div>
            <p class="text-danger p-2">* To view charges, click on the below fee sections.</p>
        </div>

        <table class="table table-bordered table-sm text-center text-muted align-middle mt-2">
            <thead class="table-light">
                <tr>
                    <th>Time Frame</th>
                    <th style="cursor: pointer;" onclick="showFareTab('{{ $indexId }}', 'CANCELLATION')">Cancellation Fee</th>
                    <th style="cursor: pointer;" onclick="showFareTab('{{ $indexId }}', 'DATECHANGE')">Date Change Fee</th>
                    <th style="cursor: pointer;" onclick="showFareTab('{{ $indexId }}', 'NO_SHOW')">No Show Fee</th>
                    <th style="cursor: pointer;" onclick="showFareTab('{{ $indexId }}', 'SEAT_CHARGEABLE')">Seat Chargeable Fee</th>
                </tr>
            </thead>
            <tbody>
                @foreach($timeFrameRows as $timeFrame => $rules)
                    @php $rowIndex = $loop->index; @endphp
                    <tr class="fare-tab-row"
                        data-index="{{ $rowIndex }}"
                        data-cancellation="{{ !empty($rules['CANCELLATION']) ? '1' : '0' }}"
                        data-datechange="{{ !empty($rules['DATECHANGE']) ? '1' : '0' }}"
                        data-noshow="{{ !empty($rules['NO_SHOW']) ? '1' : '0' }}"
                        data-seatchargeable="{{ !empty($rules['SEAT_CHARGEABLE']) ? '1' : '0' }}">
                        <th>{{ $timeFrame }}</th>
                        <td colspan="4" class="fare-content-cancellation d-none">
                            {{ $rules['CANCELLATION'] }}
                            <br><small class="text-danger">Refundable subject to cancellation penalty</small>
                        </td>
                        <td colspan="4" class="fare-content-datechange d-none">
                            {{ $rules['DATECHANGE'] }}
                        </td>
                        <td colspan="4" class="fare-content-noshow d-none">
                            {{ $rules['NO_SHOW'] }}
                        </td>
                        <td  colspan="4" class="fare-content-seatchargeable d-none">
                            {{ $rules['SEAT_CHARGEABLE'] }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div>
            <p class="text-danger p-2">Mentioned fees are Per Pax Per Sector</p>
        </div>
    </div>
</div>



        </div>
    @endforeach
     {{-- Proceed button --}}


     @php
    $priceId = $priceId1;
    $totalFare = $trip['totalPriceList'][0]['fd']['ADULT']['fC']['TF'] ?? 0;
@endphp

<input type="hidden" id="priceId_{{ $index }}" value="{{ $priceId }}">
<input type="hidden" id="initialPrice_{{ $index }}" value="{{ $totalFare }}">

    <div class="d-flex justify-content-between mt-3">
    <button class="btn btn-border-none text-white" style="background-color: #f37321;">Back</button>

  <a id="proceedBtn_{{ $index }}" href="{{ route('price', ['priceId' =>$priceId ])}}" class="btn btn-border-none text-white d-none" style="background-color: #f37321;">
    Proceed to Passenger Details >>
</a>


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
        $amountToPay =  $data['totalPriceInfo']['totalFareDetail']['fC']['TF'] ?? null;
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
    // Values passed from controller
    const SESSION_EXPIRY_TIME   = "{{ $expiryTime ?? '' }}"; // exact expiry datetime (for reference)
    const SESSION_REMAINING_SEC = {{ $remainingSeconds ?? 0 }}; // countdown in seconds
</script>

<script>
    window.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[id^="proceedBtn_"]').forEach(btn => {
            const index = btn.id.split('_')[1];

            // Auto-confirm price once page loads
            autoConfirmPrice(index);

            // Start session expiry countdown timer (only if > 0)
            if (SESSION_REMAINING_SEC > 0) {
                startExpiryTimer(SESSION_REMAINING_SEC, index);
            }
        });
    });

    function autoConfirmPrice(index) {
        const priceId      = document.getElementById('priceId_' + index).value;
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
            if (data.status === 'updated') {
                alert('The flight price has been updated to ₹' + data.newPrice + '. Please proceed.');
                document.getElementById('initialPrice_' + index).value = data.newPrice;
            }
            toggleProceed(index, true);
        })
        .catch(error => {
            console.error('Price verification failed:', error);
            toggleProceed(index, true);
        });
    }

    function toggleProceed(index, showProceed) {
        document.getElementById('loadingBtn_' + index).classList.add('d-none');
        if (showProceed) {
            document.getElementById('proceedBtn_' + index).classList.remove('d-none');
        }
    }

    function confirmPrice(index) {
        autoConfirmPrice(index);
    }
</script>

<script>
    // keep separate intervals per index (in case of multiple fares)
    const expiryIntervals = {};

    function startExpiryTimer(totalSeconds, index) {
        const expiryBar   = document.getElementById('expiryTimerBar');
        const minutesSpan = document.getElementById('timerMinutes');
        const secondsSpan = document.getElementById('timerSeconds');

        if (!expiryBar || !minutesSpan || !secondsSpan) return;

        expiryBar.classList.remove('d-none');
        let remainingSeconds = parseInt(totalSeconds, 10);

        // Initial UI update
        updateTimerUI(remainingSeconds, minutesSpan, secondsSpan);

        if (expiryIntervals[index]) clearInterval(expiryIntervals[index]);

        expiryIntervals[index] = setInterval(() => {
            remainingSeconds--;

            if (remainingSeconds < 0) {
                clearInterval(expiryIntervals[index]);
                expiryBar.classList.add('d-none');
                document.getElementById('proceedBtn_' + index)?.setAttribute('disabled', true);
                showSessionExpiryModal();
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

    function showSessionExpiryModal() {
        document.querySelector('#sessionExpiryModal .modal-body').innerHTML =
            `Your session has expired. Please refresh to get the latest price and availability.`;

        const modal = new bootstrap.Modal(document.getElementById('sessionExpiryModal'));
        modal.show();
    }
</script>
