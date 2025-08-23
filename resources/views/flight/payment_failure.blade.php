@extends('layout.layout')

@section('content')
<style>
    .failure-circle {
        width: 60px;
        height: 60px;
        background: #dc3545; /* Bootstrap Danger color */
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 30px;
        font-weight: bold;
    }
    .failure-title {
        font-size: 1.8rem;
        font-weight: bold;
        color: #dc3545;
        margin: 0;
    }
    .retry-btn {
        padding: 6px 14px;
        font-size: 0.9rem;
    }
</style>

<div class="container my-4">

    {{-- Failure Header with Light Red Background --}}
    <div class="p-4 shadow-sm rounded border mb-4" style="background-color: #f8d7da;">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div class="d-flex align-items-center">
                <div class="failure-circle me-3">✗</div>
                <div>
                    <h4 class="failure-title">Payment Failed</h4>
                    <p class="mb-0 text-danger">
                        Transaction ID: <strong>{{ $transactionId ?? 'N/A' }}</strong><br>
                        <small class="text-muted">Unfortunately, your payment could not be processed.</small>
                    </p>
                </div>
            </div>
            {{-- Retry Payment --}}
            <a href="{{ route('home') }}" class="btn btn-danger text-white fw-bold retry-btn">
                Retry Payment
            </a>
        </div>
    </div>

    {{-- Failure Details --}}
    <div class="bg-white shadow-sm rounded p-4 mb-4 border">
        <h6 class="fw-bold text-danger">Reason for Failure</h6>
        <p class="mb-2">
            {{ $failureReason ?? 'Your payment was declined by the bank or payment gateway. Please try again or use another payment method.' }}
        </p>

        <div class="mt-3">
            <h6 class="fw-bold">Next Steps</h6>
            <ul class="small ps-3 mb-0">
                <li>Check if your card/bank account has sufficient balance.</li>
                <li>Ensure that your card/bank supports online transactions.</li>
                <li>If the amount was debited, it will be refunded automatically in 3-5 business days.</li>
                <li>You may retry payment using the button above.</li>
                <li>If issue persists, contact our support with the Transaction ID.</li>
            </ul>
        </div>
    </div>

    {{-- Contact Support --}}
    <div class="card border-danger" style="background-color: rgba(252, 226, 231, 0.3);">
        <div class="card-body small text-dark">
            <p class="fw-bold mb-2 text-danger">Need Help?</p>
            <p class="mb-1">Email: 
                <a href="mailto:{{ $contactDetails['email'] ?? 'support@example.com' }}">
                    {{ $contactDetails['email'] ?? 'support@example.com' }}
                </a>
            </p>
            <p class="mb-0">Mobile: {{ $contactDetails['mobile'] ?? '+91-9999999999' }}</p>
        </div>
    </div>

</div>
@endsection
