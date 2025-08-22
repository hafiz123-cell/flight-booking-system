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
  .admin-pills .nav-link {
  font-weight: 600;
  font-size: 0.95rem;
  color: #495057;
  border-left: 4px solid transparent;
  padding: 0.5rem 1rem;
  transition: background-color 0.3s, border-color 0.3s;
  border-radius: 0 0.25rem 0.25rem 0;
  width: 100%;
  box-sizing: border-box;
  text-align: left;
}

.admin-pills .nav-link:hover {
  background-color: #f8f9fa;
  color: #212529;
}

.admin-pills .nav-link.active {
  background-color: #ff6600; /* orange background */
  color: #fff;
  border-left-color: #ff6600; /* orange left border */
}

.admin-pills .nav-link i {
  font-size: 1.1rem;
  vertical-align: middle;
  margin-right: 8px;
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
  <h4>Payments</h4>
  <div class="row mt-3">
    <div class="col-md-3">
      <!-- Nav tabs -->
      <ul class="nav flex-column nav-pills admin-pills" id="paymentTabs" role="tablist" aria-orientation="vertical">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="deposit-tab" data-bs-toggle="pill" data-bs-target="#deposit" type="button" role="tab" aria-controls="deposit" aria-selected="true">
            <i class="bi bi-cash-stack me-2"></i> Deposit
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="netbanking-tab" data-bs-toggle="pill" data-bs-target="#netbanking" type="button" role="tab" aria-controls="netbanking" aria-selected="false">
            <i class="bi bi-credit-card-2-front me-2"></i> Net-banking / Credit Card/ Debit Card
          </button>
        </li>
      </ul>
    </div>
    <div class="col-md-9">
      <!-- Tab panes -->
      <div class="tab-content">
        <div class="tab-pane fade show active" id="deposit" role="tabpanel" aria-labelledby="deposit-tab">
          <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="bi bi-credit-card me-2"></i>
            <div>
              By placing this order, you agree to our <a href="#" class="text-decoration-underline">Terms Of Use</a> and <a href="#" class="text-decoration-underline">Privacy Policy</a>
            </div>
          </div>

       <a href="{{ route('pay_link') }}" 
   class="btn btn-border-none bg-warning text-white disabled-link" 
   id="payNowBtn" 
   aria-disabled="true" 
   tabindex="-1" 
   style="pointer-events: none; opacity: 0.6;">
    Pay Now ₹{{ number_format($amount, 2) }}
</a>

<div class="form-check mt-2">
  <input class="form-check-input" type="checkbox" value="" id="termsCheck">
  <label class="form-check-label" for="termsCheck">
    I accept <a href="#" class="text-decoration-underline">terms & conditions</a>
  </label>
</div>

        </div>
        <div class="tab-pane fade" id="netbanking" role="tabpanel" aria-labelledby="netbanking-tab">
          <p>Payment options for Net-banking, Credit Card, and Debit Card will appear here.</p>
        </div>
      </div>
    </div>
  </div>
</div>


         
        {{-- Fare Summary --}}
      <div class="col-md-3">
         <h6 class="fw-bold mb-3">Fare Summary</h6>
    @php
       $priceList = $data['tripInfos'][0]['totalPriceList'][0]['fd'] ?? [];

    $adultFare = $priceList['ADULT']['fC'] ?? [];
    $childFare = $priceList['CHILD']['fC'] ?? [];
    $infantFare = $priceList['INFANT']['fC'] ?? [];

    // ✅ Base Fares
    $baseFare = ($adultFare['BF'] ?? 0) + ($childFare['BF'] ?? 0) + ($infantFare['BF'] ?? 0);

    // ✅ Taxes
    $taxAndFee = ($adultFare['TAF'] ?? 0) + ($childFare['TAF'] ?? 0) + ($infantFare['TAF'] ?? 0);

    // ✅ Total Amount
    $amountToPay = ($adultFare['TF'] ?? 0) + ($childFare['TF'] ?? 0) + ($infantFare['TF'] ?? 0);
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
            <li><strong>Base Fare:</strong> ₹{{ number_format($bf, 2) }}</li>
            <li>
                <a class="text-dark text-decoration-none" data-bs-toggle="collapse" href="#taxBreakdown" role="button">
                    <strong>Taxes & Fees:</strong> ₹{{ number_format($tf, 2) }}
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
            <li><strong>Total Amount:</strong> ₹{{ number_format($atp, 2) }}</li>

            <li class="mt-2">
                <a class="text-dark text-decoration-none" data-bs-toggle="collapse" href="#amountBreakdown" role="button">
                    <strong>Amount Breakdown</strong>
                    <i class="fa fa-chevron-down float-end"></i>
                </a>
                <div class="collapse mt-2" id="amountBreakdown">
                    <ul class="list-unstyled ms-3">
                        <li>Commission: -₹{{ number_format($commission, 2) }}</li>
                        <li>TDS: +₹{{ number_format($tds, 2) }}</li>
                        <li><strong>Net Price: ₹{{ number_format($atp, 2) }}</strong></li>
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





<script>
  document.addEventListener('DOMContentLoaded', function() {
    const termsCheckbox = document.getElementById('termsCheck');
    const payNowLink = document.getElementById('payNowBtn');

    // Initially disable link (done inline with pointer-events:none and opacity)

    termsCheckbox.addEventListener('change', function() {
      if (this.checked) {
        // Enable link
        payNowLink.style.pointerEvents = 'auto';
        payNowLink.style.opacity = '1';
        payNowLink.setAttribute('aria-disabled', 'false');
        payNowLink.removeAttribute('tabindex');
        payNowLink.classList.remove('disabled-link');
      } else {
        // Disable link
        payNowLink.style.pointerEvents = 'none';
        payNowLink.style.opacity = '0.6';
        payNowLink.setAttribute('aria-disabled', 'true');
        payNowLink.setAttribute('tabindex', '-1');
        payNowLink.classList.add('disabled-link');
      }
    });
  });
</script>

