@extends('layout.layout')
<style>
  .form-switch .form-check-input {
    width: 3rem !important;
  }

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

  .card-header {
    background-color: #f8f9fa;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 20px;
    border-bottom: 1px solid #ddd;
  }

  .card-body {
    padding: 20px;

    .ff-section {
      background-color: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
      margin-top: 20px;

    }

    .form-check {
      margin-top: 20px;
    }
  }

  .card-header:hover {
    background-color: #e9ecef;
  }

  .form-switch .form-check-input {
    width: 3rem;
    height: 1.5rem;
  }

  .form-switch-label {
    font-weight: bold;
    margin-left: 10px;
  }

  .card-header {
    cursor: pointer;
  }

  .section-card {
    border: 1px solid #ddd;
    border-radius: 6px;
    margin-bottom: 20px;
    overflow: hidden;
  }

  .section-header {
    background-color: #f8f9fa;
    padding: 15px 20px;
    font-weight: bold;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .section-body {
    padding: 20px;
    display: none;
  }

  .seat-btn {
    background-color: #ff6600;
    border: none;
    color: white;
    font-weight: 500;
    padding: 8px 20px;
    border-radius: 4px;
  }

  .seat-btn:hover {
    background-color: #e65c00;
  }

  .trip-label {
    font-weight: 600;
    margin-top: 10px;
  }

  .rotate {
    transform: rotate(180deg);
    transition: transform 0.3s ease;
  }

  .input-search-history {
    width: fit-content;
  }

  .search-history-row {
    background-color: #f8f9fa;
    padding: 20px;

    & input {
      background-color: #f8f9fa;

    }
  }

  .last-section-card {
    padding: 0 !important;
  }

  .bottom-content-last-card {
    padding: 0 20px 20px !important;

  }

  .clear-btn {
    border: 1px solid red;
    padding: 10px !important;
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
        <strong>{{ $item['step'] == 4 ? 'Last Step' : 'Step ' . $item['step'] }}</strong><br>
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
      <form action="{{ route('trip.store.round') }}" method="POST" id="passengerForm" novalidate>
        @csrf
        <div class="container mb-4">
          <!-- Toggle Switch -->
          <div class="mb-3 d-flex  align-items-center gap-2">
            <h4 class="case-swtich m-0">Expand</h4>
            <div class="form-check form-switch">
              <label class="form-switch-label" for="toggleAllSwitch"></label>
              <input class="form-check-input" type="checkbox" role="switch" id="toggleAllSwitch">
            </div>
          </div>

          <!-- Cards Container -->
          <div id="cardsContainer"></div>

          <!-- Section 1 -->
          <div class="section-card">
            <div class="section-header">Add Baggage, Meal & Other Services</div>

            @foreach($priceData as $index => $trip)
            @php
            $flightList = $trip['sI'] ?? [];
            $firstSeg = $flightList[0] ?? [];
            $lastSeg = end($flightList);
            $fromCity = $firstSeg['da']['cityCode'] ?? 'N/A';
            $toCity = $lastSeg['aa']['cityCode'] ?? 'N/A';
            $baggageList = $firstSeg['ssrInfo']['BAGGAGE'] ?? [];
            @endphp
            <div class="trip-container m-4" data-trip-index="{{ $index }}" style="{{ $index === 0 ? '' : 'display:none;' }}">
              <div class="trip-label">

                <div class="flight-tabs d-flex mb-3">
                  <button type="button" class="tab-btn active me-3 p-3" style="background-color: #f5f5f5; border-bottom: 1px solid #e65c00;" data-trip-index="0">{{ $fromCity }} → {{ $toCity }}</button>
                  <button type="button" class="tab-btn" data-trip-index="1">{{ $toCity }} → {{ $fromCity }}</button>
                </div>

              </div>
              <div class="passenger-sections" id="passenger-sections-{{ $index }}"></div>
            </div>
            @endforeach
          </div>




          <!-- Section 3 -->
          <div class="section-card">
            <div class="section-header" onclick="toggleSection(this)">
              Contact Details
              <i class="fas fa-chevron-down"></i>
            </div>
            <div class="section-body">
              <div class="row">
                <!-- Country Code Dropdown -->




                <div class="col-md-4 mb-3">
                  <div class="inner-col-datail">
                    <label class="form-label">Country Code</label>
                    <select name="country_name" id="country_code" class="form-select" required>
                      @foreach($countries as $country)
                      <option value="{{ $country->dial_code }}"
                        data-name="{{ $country->name }}"
                        {{ $country->name === 'India' ? 'selected' : '' }}>
                        {{ $country->name }} (+{{ $country->dial_code }})
                      </option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="col-md-4 mb-3">
                  <div class="inner-col-datail">
                    <label class="form-label">Mobile Number *</label>
                    <input type="text"
                      class="form-control @error('mobile') is-invalid @enderror"
                      name="mobile"
                      placeholder="Enter number"
                      value="{{ old('mobile') }}"
                      required>
                    <div id="phone-error" style="color: red; font-size: 0.9em; margin-top: 4px;"></div>
                    @error('mobile')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                    @enderror
                  </div>
                </div>

                <!-- Email -->
                <div class="col-md-4 mb-3">
                  <div class="inner-col-datail">
                    <label class="form-label">Email ID *</label>
                    <input type="email" class="form-control" name="email" placeholder="contact@itsmytrip.net" required>
                  </div>
                </div>
              </div>
            </div>
          </div>


          <!-- Section 4: GST -->
          <div class="section-card">
            <div class="section-header" onclick="toggleSection(this)">
              GST Number for Business Travel (Optional)
              <i class="fas fa-chevron-down"></i>
            </div>
            <div class="section-body last-section-card">
              <!-- History Input -->
              <div class="row mb-3 search-history-row">
                <div class="col-md-12">
                  <div class="inner-col-datail justify-content-between d-flex -align-items">
                    <input type="text" class="form-control input-search-history" name="gst_history" placeholder="Select from History">
                    <button class="btn btn-link text-danger p-0 clear-btn ms-2" type="button">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 7l16 0" />
                        <path d="M10 11l0 6" />
                        <path d="M14 11l0 6" />
                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>

              <div class="bottom-content-last-card">
                <p class="text-muted">
                  To claim credit of GST charged by airlines, please enter your company’s GST number
                </p>

                <!-- GST Details -->
                <div class="row g-3 mb-3">
                  <div class="col-md-6 col-lg-3">
                    <div class="inner-col-datail">
                      <input type="text" class="form-control" name="gst_number" placeholder="Registered Number">
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-3">
                    <div class="inner-col-datail">
                      <input type="text" class="form-control" name="gst_company_name" placeholder="Registered Company Name">
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-3">
                    <div class="inner-col-datail">
                      <input type="email" class="form-control" name="gst_email" placeholder="Registered Email">
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-3">
                    <div class="inner-col-datail">
                      <input type="text" class="form-control" name="gst_phone" placeholder="Registered Phone">
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-3">
                    <div class="inner-col-datail">
                      <input type="text" class="form-control" name="gst_address_line1" placeholder="Registered Address">
                    </div>
                  </div>
                </div>

                <!-- Address Line 2 -->
                <div class="mb-2">
                  <div class="inner-col-datail">
                    <label class="form-label">Registered Address</label>
                    <input type="text" class="form-control" name="gst_address_line2" placeholder="Registered Address">
                  </div>
                </div>

                <!-- Save Checkbox -->
                <div class="form-check mt-3">
                  <input class="form-check-input" type="checkbox" name="save_gst" id="saveGst" value="1" checked>
                  <label class="form-check-label text-danger" for="saveGst">
                    <strong>Save GST Details</strong>
                  </label>
                </div>
              </div>
            </div>
          </div>

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
          <!-- Your form inputs for passenger data, services, contact, etc. -->

          <div class="d-flex justify-content-between mt-3">
            <button type="button" class="btn btn-border-none text-white" style="background-color: #f37321;">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chevrons-left">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M11 7l-5 5l5 5" />
                <path d="M17 7l-5 5l5 5" />
              </svg>
              <span>Back</span>
            </button>

            <!-- Proceed Button -->
            <button
              type="submit"
              id="proceedBtn_{{ $index }}"
              class="btn btn-border-none text-white"
              style="background-color: #f37321;">
              <span>Proceed To Review</span>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chevrons-right">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M7 7l5 5l-5 5" />
                <path d="M13 7l5 5l-5 5" />
              </svg>
            </button>

            <!-- Validating Button -->
            <button
              type="button"
              id="loadingBtn_{{ $index }}"
              class="btn btn-border-none text-white d-none"
              style="background-color: #f37321;"
              disabled>
              <span class="spinner-border spinner-border-sm me-2"></span> Validating...
            </button>
          </div>
        </div>

    </div>

    {{-- Fare Summary --}}
    <div class="col-xl-3">
      <h4 class="fw-bold mb-3">Fare Summary</h4>
      @php
      $tripData = $data['tripInfos'] ?? [];

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

      // Total Amount
      $amountToPay = $data['totalPriceInfo']['totalFareDetail']['fC']['TF'] ?? 0;
      $netPrice = $amountToPay - $commission + $tds;
      @endphp

      <div class="bg-white shadow-sm rounded mb-4 border p-3">
        <ul class="list-unstyled small mb-2">
          <li class="text-muted d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><strong class="my-1 fw-semibold" style="font-size: 14px;">Total Base Fare:</strong> <span>₹{{ number_format($onwardBaseFare + $returnBaseFare, 2) }}</span></li>
          <li class="text-muted d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><strong class="my-1 fw-semibold" style="font-size: 14px;">Total Taxes & Fees:</strong> <span>₹{{ number_format($onwardTaxes + $returnTaxes, 2) }}</span></li>

          <a class="text-dark text-decoration-none my-1 fw-semibold" style="font-size: 14px;" data-bs-toggle="collapse" href="#taxBreakdown" role="button">
            <strong>Detailed Tax Breakdown:</strong>
            <i class="fa fa-chevron-down float-end"></i>
          </a>
          <div class="collapse mt-2" id="taxBreakdown">
            <ul class="list-unstyled ms-3">
              @if($yrTax) <li class="text-muted mb-2 d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><span>YR Tax:</span> <span>₹{{ number_format($yrTax, 2) }}</span></li> @endif
              @if($otherTaxes) <li class="text-muted mb-2 d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><span>Other Taxes:</span> <span>₹{{ number_format($otherTaxes, 2) }}</span></li> @endif
              @if($airlineGst) <li class="text-muted mb-2 d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><span>Airline GST:</span> <span>₹{{ number_format($airlineGst, 2) }}</span></li> @endif
              @if($ftcTax) <li class="text-muted mb-2 d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><span>FTC:</span><span>₹{{ number_format($ftcTax, 2) }}</span></li> @endif
              @if($mgmtFee) <li class="text-muted mb-2 d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><span>Management Fee:</span> <span>₹{{ number_format($mgmtFee, 2) }}</span></li> @endif
              @if($mgmtFeeTax) <li class="text-muted mb-2 d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><span>Mgmt Fee Tax:</span> <span>₹{{ number_format($mgmtFeeTax, 2) }}</span></li> @endif
            </ul>
          </div>
        </ul>

        <div style="border-bottom:1px solid #f1f1f1; margin:10px;"></div>

        <input type="hidden" name="amount" value="{{ $amountToPay }}">

        <ul class="list-unstyled small mb-2">
          <li class="text-muted d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><strong class="my-1 fw-semibold" style="font-size: 14px;">Total Amount:</strong> <span>₹{{ number_format($amountToPay, 2) }}</span></li>

          <li class="mt-2">
            <a class="text-dark text-decoration-none my-1 fw-semibold" style="font-size: 14px;" data-bs-toggle="collapse" href="#amountBreakdown" role="button">
              <strong>Amount Breakdown</strong>
              <i class="fa fa-chevron-down float-end"></i>
            </a>
            <div class="collapse mt-2" id="amountBreakdown">
              <ul class="list-unstyled ms-3">
                <li class="text-muted mb-2 d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><span>Commission:</span> <span>-₹{{ number_format($commission, 2) }}</span></li>
                <li class="text-muted mb-2 d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><span>TDS:</span> <span>+₹{{ number_format($tds, 2) }}</span></li>
                <li class="text-muted mb-2 d-flex justify-content-between gap-2" style="font-size: 13px; line-height: 1;"><strong class="fw-semibold" style="font-size: 14px;">Net Price:</strong><strong class="fw-semibold" style="font-size: 14px;">₹{{ number_format($netPrice, 2) }}</strong></li>
              </ul>
            </div>
          </li>
        </ul>
      </div>
    </div>

    </form>
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
  <script>
    const passengerCounts = @json($passengerCounts);
    const baggageOptions = @json($baggageList); // Should contain [{ code: '15KG', amount: 8190 }, ...]
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const cardsContainer = document.getElementById('cardsContainer');

      if (!cardsContainer) {
        console.error('cardsContainer not found in DOM.');
        return;
      }

      const passengerCounts = @json($passengerCounts);
      const {
        adults = 1, children = 0, infants = 0
      } = passengerCounts || {};
      let passengerIndex = 0;

      function createCard(type, index) {
        const currentIndex = passengerIndex;
        const cardId = `travelerForm${currentIndex}`;
        const iconId = `toggleIcon${currentIndex}`;
        const headerNameId = `cardHeaderName${currentIndex}`;
        const isFirst = currentIndex === 0;
        const showClass = isFirst ? 'show' : '';
        const iconClass = isFirst ? 'fa-chevron-up' : 'fa-chevron-down';

        const titleOptions =
          type === 'ADULT' ?
          `<option>Mr</option><option>Ms</option><option>Mrs</option>` :
          `<option>Ms</option><option>Master</option>`;

        const infantDOBInput = type === 'INFANT' ? `
      <div class="row mb-3">
        <div class="col-md-4 col-lg-3">
          <label class="form-label">Date of Birth</label>
          <input type="date"
                 class="form-control"
                 name="passengers[${currentIndex}][dob]"
                 max="${new Date().toISOString().split('T')[0]}"
                 required>
        </div>
      </div>
    ` : '';

        const cardHTML = `
      <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center"
             data-bs-toggle="collapse"
             data-bs-target="#${cardId}"
             aria-expanded="${isFirst ? 'true' : 'false'}"
             aria-controls="${cardId}">
          <span id="${headerNameId}"><strong>${type} ${index}</strong></span>
          <i class="fa-solid ${iconClass}" id="${iconId}"></i>
        </div>
        <div class="collapse ${showClass}" id="${cardId}">
          <div class="card-body position-relative">

            <!-- Hidden passenger type -->
            <input type="hidden" name="passengers[${currentIndex}][type]" value="${type}">

            <div class="mb-3">
              <input type="search"
                     class="form-control traveller-search"
                     placeholder="Search travellers..."
                     autocomplete="off"
                     name="passenger_search_${currentIndex}">
              <div class="list-group search-results mt-2" style="position: absolute; z-index: 1000;"></div>
            </div>

            <div class="row mb-3 titles-row">
              <div class="col-md-4 col-lg-3">
                <label class="form-label">Title</label>
                <select class="form-select" name="passengers[${currentIndex}][title]" required>
                  <option selected disabled value="">Title</option>
                  ${titleOptions}
                </select>
              </div>

              <div class="col-md-4 col-lg-3">
                <label class="form-label">First Name</label>
                <input type="text"
                       class="form-control"
                       name="passengers[${currentIndex}][first_name]"
                       placeholder="First Name"
                       required>
              </div>

              <div class="col-md-4 col-lg-3">
                <label class="form-label">Last Name</label>
                <input type="text"
                       class="form-control"
                       name="passengers[${currentIndex}][last_name]"
                       placeholder="Last Name"
                       required>
              </div>
            </div>

            ${infantDOBInput}

            <div class="form-check mt-3">
              <input class="form-check-input" type="checkbox" name="passengers[${currentIndex}][save]" value="1" checked>
              <label class="form-check-label">
                <strong>Add this to My Travellers List</strong>
              </label>
            </div>
          </div>
        </div>
      </div>
    `;

        cardsContainer.insertAdjacentHTML('beforeend', cardHTML);

        const firstNameInput = document.querySelector(`input[name="passengers[${currentIndex}][first_name]"]`);
        const lastNameInput = document.querySelector(`input[name="passengers[${currentIndex}][last_name]"]`);
        const headerLabel = document.getElementById(headerNameId);
        const searchInput = document.querySelector(`input[name="passenger_search_${currentIndex}"]`);
        const resultsContainer = searchInput.nextElementSibling;
        const cardElement = document.getElementById(cardId);

        const updateHeader = () => {
          const first = firstNameInput.value.trim();
          const last = lastNameInput.value.trim();
          const fullName = `${first} ${last}`.trim();
          headerLabel.innerHTML = `<strong>${type} ${index}</strong>${fullName ? ' - ' + fullName : ''}`;
        };

        firstNameInput.addEventListener('input', () => {
          firstNameInput.value = firstNameInput.value.toUpperCase();
          updateHeader();
        });

        lastNameInput.addEventListener('input', () => {
          lastNameInput.value = lastNameInput.value.toUpperCase();
          updateHeader();
        });

        searchInput.addEventListener('input', () => {
          const query = searchInput.value.trim();
          resultsContainer.innerHTML = '';
          if (!query) return;

          fetch(`/traveller-search?type=${type.toLowerCase()}&q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
              resultsContainer.innerHTML = '';
              data.forEach(t => {
                const item = document.createElement('button');
                item.type = 'button';
                item.className = 'list-group-item list-group-item-action';
                item.textContent = `${t.title} ${t.first_name} ${t.last_name}`;
                item.addEventListener('click', () => {
                  cardElement.querySelector(`select[name="passengers[${currentIndex}][title]"]`).value = t.title;
                  cardElement.querySelector(`input[name="passengers[${currentIndex}][first_name]"]`).value = t.first_name.toUpperCase();
                  cardElement.querySelector(`input[name="passengers[${currentIndex}][last_name]"]`).value = t.last_name.toUpperCase();

                  if (type === 'INFANT' && t.dob) {
                    const dobInput = cardElement.querySelector(`input[name="passengers[${currentIndex}][dob]"]`);
                    if (dobInput) dobInput.value = t.dob;
                  }

                  ['first_name', 'last_name'].forEach(field => {
                    const input = cardElement.querySelector(`input[name="passengers[${currentIndex}][${field}]"]`);
                    if (input) input.dispatchEvent(new Event('input'));
                  });

                  resultsContainer.innerHTML = '';
                  searchInput.value = '';
                });
                resultsContainer.appendChild(item);
              });
            })
            .catch(err => console.error('Error fetching traveller data:', err));
        });

        passengerIndex++;
      }

      for (let i = 1; i <= adults; i++) createCard('ADULT', i);
      for (let i = 1; i <= children; i++) createCard('CHILD', i);
      for (let i = 1; i <= infants; i++) createCard('INFANT', i);

      for (let i = 0; i < passengerIndex; i++) {
        const collapseEl = document.getElementById(`travelerForm${i}`);
        const iconEl = document.getElementById(`toggleIcon${i}`);
        const collapseInstance = bootstrap.Collapse.getOrCreateInstance(collapseEl, {
          toggle: false
        });
        if (i !== 0) collapseInstance.hide();
        collapseEl.addEventListener('show.bs.collapse', () => iconEl.classList.replace('fa-chevron-down', 'fa-chevron-up'));
        collapseEl.addEventListener('hide.bs.collapse', () => iconEl.classList.replace('fa-chevron-up', 'fa-chevron-down'));
      }

      const toggleSwitch = document.getElementById('toggleAllSwitch');
      if (toggleSwitch) {
        toggleSwitch.addEventListener('change', () => {
          const turnOn = toggleSwitch.checked;
          for (let i = 0; i < passengerIndex; i++) {
            const collapseEl = document.getElementById(`travelerForm${i}`);
            const instance = bootstrap.Collapse.getOrCreateInstance(collapseEl, {
              toggle: false
            });
            turnOn ? instance.show() : instance.hide();
          }
        });
      }

      const form = document.getElementById('passengerForm');
      if (form) {
        form.addEventListener('submit', (e) => {
          let valid = true;
          form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
          form.querySelectorAll('.validation-msg').forEach(el => el.remove());

          const requiredFields = form.querySelectorAll('input[required], select[required]');
          requiredFields.forEach(input => {
            if (!input.value.trim()) {
              valid = false;
              input.classList.add('is-invalid');
              const error = document.createElement('div');
              error.className = 'text-danger small validation-msg';
              error.textContent = 'This field is required.';
              input.parentElement.appendChild(error);

              const removeError = () => {
                input.classList.remove('is-invalid');
                const msg = input.parentElement.querySelector('.validation-msg');
                if (msg) msg.remove();
                input.removeEventListener('input', removeError);
                input.removeEventListener('change', removeError);
              };
              input.addEventListener('input', removeError);
              input.addEventListener('change', removeError);
            }
          });

          if (!valid) {
            e.preventDefault();
            e.stopPropagation();
          }
        });
      }
    });
  </script>

  <script>
    function toggleSection(header) {
      const body = header.nextElementSibling;
      const icon = header.querySelector('i');

      if (body.style.display === 'block') {
        body.style.display = 'none';
        icon.classList.remove('rotate');
      } else {
        body.style.display = 'block';
        icon.classList.add('rotate');
      }
    }
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Dynamic passenger counts from backend
      const rawCounts = @json($passengerCounts);
      const allBaggageLists = @json(array_map(fn($trip) => $trip['sI'][0]['ssrInfo']['BAGGAGE'] ?? [], $priceData));
      const allMealLists = @json(array_map(fn($trip) => $trip['sI'][0]['ssrInfo']['MEAL'] ?? [], $priceData));

      // Normalize passenger types
      const normalizeKey = (key) => {
        key = key.toLowerCase();
        if (key === 'adults' || key === 'adult') return 'adult';
        if (key === 'children' || key === 'child') return 'child';
        if (key === 'infants' || key === 'infant') return 'infant';
        return key;
      };

      const passengerCounts = {};
      Object.entries(rawCounts).forEach(([key, value]) => {
        passengerCounts[normalizeKey(key)] = Number(value) || 0;
      });

      // Create passenger card with baggage & meal
      function createPassengerCard(type, index, baggageOptions, mealOptions, tripIndex, passengerIndex) {
        if (type === 'infant') return '';
        const label = `${type.toUpperCase()} ${index + 1}`;
        const showLabels = type === 'adult' && index === 0;

        // Baggage dropdown
        let baggageHtml = '<option value="">Add Baggage</option>';
        baggageOptions.forEach(b => {
          const textOnly = `${b.desc} ${b.weight || ''}`.trim();
          baggageHtml += `<option value="${textOnly}" data-amount="${b.amount}">${textOnly}</option>`;
        });

        // Meal dropdown
        let mealHtml = '';
        if (mealOptions.length > 0) {
          mealHtml += `<select class="form-select ms-2 meal-select" name="meal_${tripIndex}_${passengerIndex}">`;
          mealHtml += '<option value="">Add Meal</option>';
          mealOptions.forEach(m => {
            const textOnly = `${m.desc}`.trim();
            mealHtml += `<option value="${textOnly}" data-amount="${m.amount}">${textOnly}</option>`;
          });
          mealHtml += '</select>';
        }

        return `
            <div class="row align-items-center mt-3" data-passenger-index="${passengerIndex}">
                <div class="col-md-3"><strong>${label}</strong></div>
                <div class="col-md-9">
                    <div class="d-flex align-items-center">
                        <div class="me-2">
                            ${showLabels ? '<label class="form-label">Baggage</label>' : ''}
                            <select class="form-select baggage-select" name="baggage_${tripIndex}_${passengerIndex}">
                                ${baggageHtml}
                            </select>
                        </div>
                        ${mealHtml ? `<div>${showLabels ? '<label class="form-label">Meal</label>' : ''}${mealHtml}</div>` : ''}
                    </div>
                </div>
            </div>
        `;
      }

      // Render passenger cards
      document.querySelectorAll('.trip-container').forEach(container => {
        const tripIndex = container.dataset.tripIndex;
        const baggageOptions = allBaggageLists[tripIndex] || [];
        const mealOptions = allMealLists[tripIndex] || [];
        const sectionContainer = container.querySelector(`#passenger-sections-${tripIndex}`);
        let passengerIndex = 0;

        Object.entries(passengerCounts).forEach(([type, count]) => {
          if (!count) return;
          for (let i = 0; i < count; i++) {
            const card = createPassengerCard(type, i, baggageOptions, mealOptions, tripIndex, passengerIndex);
            if (card) {
              sectionContainer.insertAdjacentHTML('beforeend', card);
              passengerIndex++;
            }
          }
        });
      });

      // Build structured JSON before submit
      const form = document.querySelector('#passengerForm');
      if (form) {
        form.addEventListener('submit', () => {
          const passengers = [];
          const passengerRows = document.querySelectorAll('[data-passenger-index]');

          passengerRows.forEach((row, idx) => {
            if (!passengers[idx]) passengers[idx] = {
              title: '',
              type: '',
              first_name: '',
              last_name: '',
              flights: []
            };

            const tripContainers = document.querySelectorAll('.trip-container');
            tripContainers.forEach((tc, tripIndex) => {
              const baggageSelect = tc.querySelector(`select[name="baggage_${tripIndex}_${idx}"]`);
              const mealSelect = tc.querySelector(`select[name="meal_${tripIndex}_${idx}"]`);

              const baggage = baggageSelect?.value || null;
              const baggageAmount = baggageSelect && baggageSelect.selectedIndex > 0 ?
                Number(baggageSelect.selectedOptions[0].dataset.amount) :
                null;

              const meal = mealSelect?.value || null;
              const mealAmount = mealSelect && mealSelect.selectedIndex > 0 ?
                Number(mealSelect.selectedOptions[0].dataset.amount) :
                null;

              passengers[idx].flights[tripIndex] = {
                baggage,
                baggage_amount: baggageAmount,
                meal,
                meal_amount: mealAmount
              };
            });
          });

          // Hidden field with JSON
          let hiddenInput = document.querySelector('#passenger_json');
          if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'passenger_json';
            hiddenInput.id = 'passenger_json';
            form.appendChild(hiddenInput);
          }
          hiddenInput.value = JSON.stringify(passengers);
        });
      }

      // Flight tab toggle
      const tabButtons = document.querySelectorAll('.tab-btn');
      tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
          const index = btn.dataset.tripIndex;
          tabButtons.forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
          document.querySelectorAll('.trip-container').forEach(tc => {
            tc.style.display = tc.dataset.tripIndex === index ? '' : 'none';
          });
        });
      });
    });
  </script>



  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const form = document.getElementById("passengerForm"); // your form ID
      if (!form) return;

      form.addEventListener("submit", function(e) {
        const proceedBtn = form.querySelector("[id^='proceedBtn_']");
        const loadingBtn = form.querySelector("[id^='loadingBtn_']");

        if (!proceedBtn || !loadingBtn) return;

        // Show validating button
        proceedBtn.classList.add("d-none");
        loadingBtn.classList.remove("d-none");
      });
    });
    form.addEventListener("submit", function(e) {
      const proceedBtn = form.querySelector("[id^='proceedBtn_']");
      const loadingBtn = form.querySelector("[id^='loadingBtn_']");
      proceedBtn.classList.add("d-none");
      loadingBtn.classList.remove("d-none");

      form.querySelectorAll("input, select, textarea, button").forEach(el => el.disabled = true);
    });
  </script>


  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const countrySelect = document.getElementById('country_code');
      const mobileInput = document.querySelector('input[name="mobile"]');
      const errorDiv = document.getElementById('phone-error');

      let countryPhoneLengths = {};

      // Fetch phone lengths from Laravel route
      fetch('/phone-lengths')
        .then(response => {
          if (!response.ok) throw new Error('Network error');
          return response.json();
        })
        .then(data => {
          countryPhoneLengths = data;
        })
        .catch(err => {
          console.error('Failed to load country phone lengths:', err);
        });

      function validatePhoneLength() {
        const selectedOption = countrySelect.options[countrySelect.selectedIndex];
        const countryName = selectedOption.getAttribute('data-name');
        const phoneDigits = mobileInput.value.replace(/\D/g, '');

        let message = '';

        if (!countryPhoneLengths.hasOwnProperty(countryName)) {
          // No config: length must be between 8 and 15 digits
          if (phoneDigits.length < 8 || phoneDigits.length > 15) {
            message = 'Phone number must be between 8 and 15 digits';
          }
        } else {
          const expectedLength = countryPhoneLengths[countryName];
          if (phoneDigits.length !== expectedLength) {
            message = `Phone number must be exactly ${expectedLength} digits`;
          }
        }

        if (message) {
          errorDiv.textContent = message;
          mobileInput.setCustomValidity(message);
        } else {
          mobileInput.setCustomValidity('');
          errorDiv.textContent = '';
        }
      }

      mobileInput.addEventListener('input', validatePhoneLength);

      countrySelect.addEventListener('change', () => {
        mobileInput.value = '';
        mobileInput.setCustomValidity('');
        errorDiv.textContent = '';
      });
    });
  </script>