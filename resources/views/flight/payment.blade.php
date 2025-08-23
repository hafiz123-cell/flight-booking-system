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
        background-color: #ff6600;
        color: #fff;
        border-left-color: #ff6600;
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

        {{-- Payments --}}
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
                                    By placing this order, you agree to our 
                                    <a href="#" class="text-decoration-underline">Terms Of Use</a> and 
                                    <a href="#" class="text-decoration-underline">Privacy Policy</a>
                                </div>
                            </div>

                            {{-- ✅ Pay Now Button with Final Amount --}}
                            <a href="{{ route('pay_link') }}" 
                                class="btn btn-border-none bg-warning text-white disabled-link" 
                                id="payNowBtn" 
                                aria-disabled="true" 
                                tabindex="-1" 
                                style="pointer-events: none; opacity: 0.6;">
                                Pay Now ₹{{ number_format($finalAmount, 2) }}
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
            <div class="bg-white shadow-sm rounded mb-4 border p-3">
                <ul class="list-unstyled small mb-2">
                    <li><strong>Base Fare:</strong> ₹{{ number_format($baseFare, 2) }}</li>
                    <li><strong>Taxes & Fees:</strong> ₹{{ number_format($taxAndFee, 2) }}</li>

                    @if($extraCharges > 0)
                        <li><strong>Extras (Meals + Baggage):</strong> ₹{{ number_format($extraCharges, 2) }}</li>
                    @endif
                </ul>

                <hr>

                <ul class="list-unstyled small mb-2">
                    <li><strong>Total Amount:</strong> ₹{{ number_format($finalAmount, 2) }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- Timer Bar --}}
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

{{-- Session Expiry Modal --}}
<div class="modal fade" id="sessionExpiryModal" tabindex="-1" aria-labelledby="sessionExpiryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sessionExpiryModalLabel">Session Expired</h5>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="window.location.href='{{route('tripjack.search')}}'">Back to flight list</button>
        <button type="button" class="btn btn-primary" onclick="window.location.reload();">Continue</button>
      </div>
    </div>
  </div>
</div>

@endsection

{{-- ✅ Scripts for session expiry & terms --}}
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const termsCheckbox = document.getElementById('termsCheck');
    const payNowLink = document.getElementById('payNowBtn');

    termsCheckbox.addEventListener('change', function() {
      if (this.checked) {
        payNowLink.style.pointerEvents = 'auto';
        payNowLink.style.opacity = '1';
        payNowLink.setAttribute('aria-disabled', 'false');
        payNowLink.removeAttribute('tabindex');
        payNowLink.classList.remove('disabled-link');
      } else {
        payNowLink.style.pointerEvents = 'none';
        payNowLink.style.opacity = '0.6';
        payNowLink.setAttribute('aria-disabled', 'true');
        payNowLink.setAttribute('tabindex', '-1');
        payNowLink.classList.add('disabled-link');
      }
    });
  });
</script>
