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

    .active-fare-tab {
        border-bottom: 2px solid #f37321;
        font-weight: bold;
        color: #f37321 !important;
    }

    .active-fare-type {
        background-color: #f37321 !important;
        color: white !important;
        font-weight: bold;
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
                <strong>{{ $item['step'] == 4 ? 'FINISH STEP' : 'STEP ' . $item['step'] }}</strong>
                <br>
                <span class="d-none d-lg-block">{{ $item['label'] }}</span>
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

            @php

            $totalFare = $tripData['totalPriceInfo']['totalFareDetail']['fC']['TF'] ?? 0;
            @endphp

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

                {{-- Header --}}
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
                <div class="row mb-3">

                    <div class="col-12">
                        <div class="d-flex flex-column justify-content-start gap-3">
                            <div class="d-flex flex-column flex-lg-row justify-content-start justify-content-lg-between gap-3 gap-lg-4">
                                <div class="d-flex flex-row flex-lg-column gap-4 justify-content-between justify-content-lg-start">
                                    <div class="d-flex gap-0 flex-column">
                                        <img src="{{ asset("AirlinesLogo/$airlineCode.png") }}" onerror="this.src='{{ asset("AirlinesLogo/default.png") }}'" class="img-fluid" style="max-height: 40px; max-width: 40px;">
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
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between gap-4">
                                    <div class="d-flex gap-0 flex-column align-items-start align-items-sm-end">
                                        <h5 class="my-1 fw-semibold" style="font-size: 14px;">{{ $depTime }}</h5>
                                        <small class="text-muted mt-1" style="font-size: 11px; line-height: 1;">{{ $depCity }}</small>
                                        <small class="text-muted mt-2" style="font-size: 11px; line-height: 1;">{{ $depAirport }}</small>
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
                                        <small class="text-muted mt-2" style="font-size: 11px; line-height: 1;">{{ $arrAirport }}</small>
                                        @if($arrTerminal)<small class="text-muted mt-2" style="font-size: 11px; line-height: 1;">{{ $arrTerminal }}</small>@endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <div style="border-bottom:1px solid #f1f1f1; margin:10px;"></div>
                @endforeach
                @endforeach
                @php
                $fareRulesList = $tripData[0]['totalPriceList'] ?? [];
                @endphp

                <div class="mt-2">
                    <button type="button" class="btn btn-sm" style="background-color: #f5f5f5;color: rgb(255, 138, 5);width: fit-content;font-size: 11px;padding: 4px 8px 3px;" onclick="toggleDetails('fare_rules_container', this)">Fare Rules +</button>


                    <div id="fare_rules_container" class="d-none bg-light p-2 rounded mt-2">
                        <div class="mt-3">
                            {{-- Outer tabs (DEL → BOM / BOM → DEL) --}}
                            <div class="d-flex justify-content-between p-2">
                                <div class="d-flex">
                                    @foreach($tripData as $tripIndex => $trip)
                                    @php
                                    $flightList = $trip['sI'];
                                    $firstSeg = $flightList[0];
                                    $lastSeg = end($flightList);
                                    $fromCity = $firstSeg['da']['cityCode'] ?? '';
                                    $toCity = $lastSeg['aa']['cityCode'] ?? '';
                                    @endphp

                                    <small
                                        class="fare-tab-btn text-danger me-2 bg-light p-2 border-bottom {{ $tripIndex == 0 ? 'active-fare-tab' : '' }}"
                                        style="width:120px; background-color: white; cursor:pointer;"
                                        onclick="showFareTable('{{ $tripIndex }}', this)">
                                        {{ $fromCity . ' → ' . $toCity }}
                                    </small>
                                    @endforeach

                                </div>
                                <div style="background-color: white;">
                                    <button class="btn btn-light bg-light border-none" style="color:#f37321; background-color: white;">
                                        Detailed View +
                                    </button>
                                </div>
                            </div>

                            {{-- Fare Rules Tables --}}
                            @foreach($tripData as $pIndex => $trip)
                            @php
                            $priceItem = $trip['totalPriceList'][0] ?? [];
                            $fareRules = $priceItem['fareRuleInformation']['tfr'] ?? [];
                            $timeFrameRows = [];
                            foreach (['CANCELLATION','DATECHANGE','NO_SHOW','SEAT_CHARGEABLE'] as $ruleType) {
                            if (!empty($fareRules[$ruleType])) {
                            foreach ($fareRules[$ruleType] as $rule) {
                            $start = $rule['st'] ?? 0;
                            $end = $rule['et'] ?? 0;

                            $startFrame = ($start < 24) ? "{$start} hrs" : intval($start / 24) . " days" ;
                                $endFrame=($end < 24) ? "{$end} hrs" : intval($end / 24) . " days" ;
                                if ($start==0 && $end==0) {
                                $startFrame='Immediate' ;
                                $endFrame='' ;
                                }

                                $timeFrameKey=$startFrame . ($endFrame ? ' to ' . $endFrame : '' );
                                if (!isset($timeFrameRows[$timeFrameKey])) {
                                $timeFrameRows[$timeFrameKey]=[ 'CANCELLATION'=> null,
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
                                $timeFrameRows[$timeFrameKey]['DATECHANGE'] = '₹' . $amount . ' + ₹' . $fee . ' + Fare Difference + Taxes';
                                } elseif ($ruleType == 'NO_SHOW') {
                                $timeFrameRows[$timeFrameKey]['NO_SHOW'] = $rule['policyInfo'] ?? '-';
                                } elseif ($ruleType == 'SEAT_CHARGEABLE') {
                                $timeFrameRows[$timeFrameKey]['SEAT_CHARGEABLE'] = 'Paid Seat';
                                }
                                }
                                }
                                }
                                @endphp

                                <div id="fare-table-{{ $pIndex }}" class="fare-table-container {{ $pIndex == 0 ? '' : 'd-none' }}">
                                    <table class="table table-bordered table-sm text-center text-muted align-middle mt-2">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Time Frame</th>
                                                <th data-type="CANCELLATION" class="active-fare-type" style="cursor:pointer;" onclick="switchFareType('{{ $pIndex }}','CANCELLATION')">Cancellation</th>
                                                <th data-type="DATECHANGE" style="cursor:pointer;" onclick="switchFareType('{{ $pIndex }}','DATECHANGE')">Date Change</th>
                                                <th data-type="NO_SHOW" style="cursor:pointer;" onclick="switchFareType('{{ $pIndex }}','NO_SHOW')">No Show</th>
                                                <th data-type="SEAT_CHARGEABLE" style="cursor:pointer;" onclick="switchFareType('{{ $pIndex }}','SEAT_CHARGEABLE')">Seat</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($timeFrameRows as $timeFrame => $rules)
                                            <tr>
                                                <th>{{ $timeFrame }}</th>
                                                <td colspan="4" class="fare-content-{{ $pIndex }}-CANCELLATION">{{ $rules['CANCELLATION'] ?? '-' }}</td>
                                                <td colspan="4" class="fare-content-{{ $pIndex }}-DATECHANGE d-none">{{ $rules['DATECHANGE'] ?? '-' }}</td>
                                                <td colspan="4" class="fare-content-{{ $pIndex }}-NO_SHOW d-none">{{ $rules['NO_SHOW'] ?? '-' }}</td>
                                                <td colspan="4" class="fare-content-{{ $pIndex }}-SEAT_CHARGEABLE d-none">{{ $rules['SEAT_CHARGEABLE'] ?? '-' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endforeach
                        </div>

                    </div>
                </div>
            </div>


            {{-- Proceed button --}}
            <form id="proceedForm">
                @csrf

                @foreach($tripData as $tripIndex => $trip)
                @foreach(($trip['totalPriceList'] ?? []) as $priceIndex => $priceItem)
                @php
                $priceId = $priceItem['id'] ?? null;
                $totalFare = $priceItem['fd']['ADULT']['fC']['TF'] ?? 0;
                @endphp

                @if($priceId)
                <input type="hidden"
                    name="priceIds[]"
                    value="{{ $priceId }}">
                <input type="hidden"
                    name="initialPrices[]"
                    value="{{ $totalFare }}">
                @endif
                @endforeach
                @endforeach

                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-border-none text-white" style="background-color:#f37321;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chevrons-left">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M11 7l-5 5l5 5" />
                            <path d="M17 7l-5 5l5 5" />
                        </svg>
                        <span>Back</span>
                    </button>

                    <button type="button"
                        id="proceedBtn"
                        class="btn btn-border-none text-white"
                        style="background-color:#f37321;">
                        <span>Proceed to Passenger Details</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chevrons-right">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M7 7l5 5l-5 5" />
                            <path d="M13 7l5 5l-5 5" />
                        </svg>
                    </button>

                    <button id="loadingBtn"
                        class="btn btn-border-none text-white d-none"
                        style="background-color:#f37321;" disabled>
                        <span class="spinner-border spinner-border-sm me-2"></span> Confirming Price...
                    </button>
                </div>
            </form>


        </div>

        {{-- Fare Summary --}}
        @php
        $tripData = $data['tripInfos'] ?? [];
        $amountToPay = $data['totalPriceInfo']['totalFareDetail']['fC']['TF'] ?? 0;

        // Initialize totals
        $onwardBaseFare = $onwardTaxes = 0;
        $returnBaseFare = $returnTaxes = 0;

        $yrTax = $otherTaxes = $airlineGst = $ftcTax = $mgmtFee = $mgmtFeeTax = 0;
        $commission = 0;
        $tds = 0;

        // Loop through each trip (onward + return)
        foreach ($tripData as $index => $trip) {
        $adultFareData = $trip['totalPriceList'][0]['fd']['ADULT'] ?? [];
        $childFareData = $trip['totalPriceList'][0]['fd']['CHILD'] ?? [];

        $fC = $adultFareData['fC'] ?? [];
        $child_fc = $childFareData['fC'] ?? [];

        // Base fare and taxes & fees for this segment
        $segmentBaseFare = ($fC['BF'] ?? 0) + ($child_fc['BF'] ?? 0);
        $segmentTaxes = ($fC['TAF'] ?? 0) + ($child_fc['TAF'] ?? 0);

        if ($index === 0) {
        $onwardBaseFare = $segmentBaseFare;
        $onwardTaxes = $segmentTaxes;
        } else {
        $returnBaseFare = $segmentBaseFare;
        $returnTaxes = $segmentTaxes;
        }

        $afC_TAF = $adultFareData['afC']['TAF'] ?? [];
        $afC_NCM = $adultFareData['afC']['NCM'] ?? [];

        $yrTax += $afC_TAF['YQ'] ?? 0;
        $otherTaxes += $afC_TAF['OT'] ?? 0;
        $airlineGst += $afC_TAF['AGST'] ?? 0;
        $ftcTax += $afC_TAF['FTC'] ?? 0;
        $mgmtFee += $afC_TAF['MF'] ?? 0;
        $mgmtFeeTax += $afC_TAF['MFT'] ?? 0;

        $commission += $trip['totalPriceList'][0]['commission'] ?? 0;
        $tds += $afC_NCM['TDS'] ?? 0;
        }

        // Net Price = Total Amount - Commission + TDS
        $netPrice = $amountToPay - $commission + $tds;
        @endphp

        <div class="col-xl-3">
            <h6 class="fw-bold mb-3">Fare Summary</h6>

            <div class="bg-white shadow-sm rounded mb-4 border p-3">
                <ul class="list-unstyled small mb-2">
                    <li class="text-muted d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><strong class="my-1 fw-semibold" style="font-size: 14px;">Total Base Fare:</strong><span>₹{{ number_format($onwardBaseFare + $returnBaseFare, 2) }}</span></li>
                    <li class="text-muted d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><strong class="my-1 fw-semibold" style="font-size: 14px;">Total Taxes & Fees:</strong><span>₹{{ number_format($onwardTaxes + $returnTaxes, 2) }}</span></li>

                    <a class="text-dark text-decoration-none" data-bs-toggle="collapse" href="#taxBreakdown" role="button">
                        <strong class="my-1 fw-semibold" style="font-size: 14px;">Detailed Tax Breakdown:</strong>
                        <i class="fa fa-chevron-down float-end"></i>
                    </a>
                    <div class="collapse mt-2" id="taxBreakdown">
                        <ul class="list-unstyled ms-3">
                            @if($yrTax) <li class="text-muted mb-2 d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><span>YR Tax:</span><span>₹{{ number_format($yrTax, 2) }}</span></li> @endif
                            @if($otherTaxes) <li class="text-muted mb-2 d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><span>Other Taxes:</span><span>₹{{ number_format($otherTaxes, 2) }}</span></li> @endif
                            @if($airlineGst) <li class="text-muted mb-2 d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><span>Airline GST:</span> <span>₹{{ number_format($airlineGst, 2) }}</span></li> @endif
                            @if($ftcTax) <li class="text-muted mb-2 d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><span>FTC:</span> <span>₹{{ number_format($ftcTax, 2) }}</span></li> @endif
                            @if($mgmtFee) <li class="text-muted mb-2 d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><span>Management Fee:</span> <span>₹{{ number_format($mgmtFee, 2) }}</span></li> @endif
                            @if($mgmtFeeTax) <li class="text-muted mb-2 d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><span>Mgmt Fee Tax:</span> <span>₹{{ number_format($mgmtFeeTax, 2) }}</span></li> @endif
                        </ul>
                    </div>
                </ul>

                <div style="border-bottom:1px solid #f1f1f1; margin:10px;"></div>

                <ul class="list-unstyled small mb-2">
                    <li class="text-muted d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><strong class="my-1 fw-semibold" style="font-size: 14px;">Total Amount:</strong><span>₹{{ number_format($amountToPay, 2) }}</span></li>

                    <li class="mt-2">
                        <a class="text-dark text-decoration-none" data-bs-toggle="collapse" href="#amountBreakdown" role="button">
                            <strong class="my-1 fw-semibold" style="font-size: 14px;">Amount Breakdown</strong>
                            <i class="fa fa-chevron-down float-end"></i>
                        </a>
                        <div class="collapse mt-2" id="amountBreakdown">
                            <ul class="list-unstyled ms-3">
                                <li class="text-muted mb-2 d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><span>Commission:</span> <span>-₹{{ number_format($commission, 2) }}</span></li>
                                <li class="text-muted mb-2 d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><span>TDS:</span> <span>+₹{{ number_format($tds, 2) }}</span></li>
                                <li class="text-muted mb-2 d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><strong class="my-1 fw-semibold" style="font-size: 14px;">Net Price:</strong><strong>₹{{ number_format($netPrice, 2) }}</strong></li>
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
        function showFareTable(index, el) {
            // switch outer tabs (DEL→BOM / BOM→DEL)
            document.querySelectorAll('.fare-table-container').forEach(div => div.classList.add('d-none'));
            document.querySelector('#fare-table-' + index).classList.remove('d-none');

            document.querySelectorAll('.fare-tab-btn').forEach(btn => btn.classList.remove('active-fare-tab'));
            el.classList.add('active-fare-tab');
        }

        function switchFareType(pIndex, type) {
            // hide all types in this table
            ['CANCELLATION', 'DATECHANGE', 'NO_SHOW', 'SEAT_CHARGEABLE'].forEach(ruleType => {
                document.querySelectorAll('.fare-content-' + pIndex + '-' + ruleType).forEach(td => td.classList.add('d-none'));
            });

            // show selected type
            document.querySelectorAll('.fare-content-' + pIndex + '-' + type).forEach(td => td.classList.remove('d-none'));

            // highlight header
            document.querySelectorAll('#fare-table-' + pIndex + ' thead th').forEach(th => th.classList.remove('active-fare-type'));
            document.querySelector(`#fare-table-${pIndex} thead th[data-type="${type}"]`)?.classList.add('active-fare-type');
        }
    </script>

    <script>
        function toggleDetails(id, btn) {
            let el = document.getElementById(id);
            if (el.classList.contains('d-none')) {
                el.classList.remove('d-none');
                btn.textContent = btn.textContent.replace('+', '-');
            } else {
                el.classList.add('d-none');
                btn.textContent = btn.textContent.replace('-', '+');
            }
        }

        function showFareTab(fareId, type) {
            let rows = document.querySelectorAll("#fare_rules_container .fare-tab-row");
            rows.forEach(row => {
                row.querySelectorAll("td").forEach(cell => cell.classList.add("d-none"));
                let target = row.querySelector(".fare-content-" + type.toLowerCase());
                if (target) target.classList.remove("d-none");
            });
        }
    </script>
    <script>
        function showFareTable(index, el) {
            // Hide all direction tables
            document.querySelectorAll('.fare-table-container').forEach(div => div.classList.add('d-none'));
            document.getElementById('fare-table-' + index).classList.remove('d-none');

            // Highlight active direction tab
            document.querySelectorAll('.fare-tab-btn').forEach(btn => btn.classList.remove('active-fare-tab'));
            el.classList.add('active-fare-tab');
        }

        function showFareTab(pIndex, type) {
            // Hide all fare content in this table
            ['CANCELLATION', 'DATECHANGE', 'NO_SHOW', 'SEAT_CHARGEABLE'].forEach(ruleType => {
                document.querySelectorAll('.fare-content-' + pIndex + '-' + ruleType).forEach(td => td.classList.add('d-none'));
            });

            // Show only selected rule column
            document.querySelectorAll('.fare-content-' + pIndex + '-' + type).forEach(td => td.classList.remove('d-none'));
        }
    </script>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            autoConfirmAllPrices();
        });

        function autoConfirmAllPrices() {
            let priceIds = [];
            let initialPrices = [];

            document.querySelectorAll('[id^="priceId_"]').forEach((input, index) => {
                priceIds.push(input.value);
                initialPrices.push(parseFloat(document.getElementById('initialPrice_' + index).value));
            });

            // Hide proceed, show loading
            document.getElementById('proceedBtn').classList.add('d-none');
            document.getElementById('loadingBtn').classList.remove('d-none');

            fetch('/gofly/flight/review-price', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        priceIds,
                        initialPrices
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'ok') {
                        let allowProceed = true;

                        data.results.forEach((result, i) => {
                            if (result.status === 'updated') {
                                alert('Flight ' + (i + 1) + ' price updated to ₹' + result.newPrice);
                                document.getElementById('initialPrice_' + i).value = result.newPrice;
                            }
                            if (result.status === 'error') {
                                allowProceed = false;
                            }
                        });

                        document.getElementById('loadingBtn').classList.add('d-none');
                        if (allowProceed) {
                            document.getElementById('proceedBtn').classList.remove('d-none');
                        }
                    } else {
                        document.getElementById('loadingBtn').classList.add('d-none');
                        document.getElementById('proceedBtn').classList.remove('d-none');
                    }
                })
                .catch(err => {
                    console.error('Price check failed', err);
                    document.getElementById('loadingBtn').classList.add('d-none');
                    document.getElementById('proceedBtn').classList.remove('d-none');
                });
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let proceedBtn = document.getElementById('proceedBtn');
            let loadingBtn = document.getElementById('loadingBtn');

            proceedBtn.addEventListener('click', function() {
                // Show loader, hide proceed
                proceedBtn.classList.add("d-none");
                loadingBtn.classList.remove("d-none");

                // Collect priceIds
                let priceIds = [];
                document.querySelectorAll("input[name='priceIds[]']").forEach(input => {
                    priceIds.push(input.value);
                });

                // Collect initialPrices
                let initialPrices = [];
                document.querySelectorAll("input[name='initialPrices[]']").forEach(input => {
                    initialPrices.push(input.value);
                });

                console.log("Collected priceIds:", priceIds);
                console.log("Collected initialPrices:", initialPrices);

                // Redirect
                let url = "{{ route('proceed') }}" +
                    "?priceIds=" + encodeURIComponent(priceIds.join(",")) +
                    "&initialPrices=" + encodeURIComponent(initialPrices.join(","));

                window.location.href = url;
            });
        });
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
        function showFareTable(index, el) {
            // Hide all
            document.querySelectorAll('.fare-table-container').forEach(div => div.classList.add('d-none'));

            // Show selected
            document.getElementById('fare-table-' + index).classList.remove('d-none');

            // Highlight active tab
            document.querySelectorAll('.fare-tab-btn').forEach(btn => btn.classList.remove('fw-bold', 'border-dark'));
            el.classList.add('fw-bold', 'border-dark');
        }
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