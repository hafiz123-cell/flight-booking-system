<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $bookingId ?? 'N/A' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; padding: 20px; }
        .invoice-container { font-family: Arial, sans-serif; color: #333; padding: 20px; background: white; max-width: 900px; margin: auto; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);}
        .invoice-header { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .invoice-header h1 { font-size: 28px; font-weight: bold; }
        .invoice-details { text-align: right; font-size: 14px; }
        .company-info { background: #f1f4f8; padding: 15px; font-size: 14px; margin-bottom: 20px; border-radius: 4px; }
        .company-info b { color: #ff6600; }
        .company-info a { color: #ff6600; text-decoration: none; }
        table.invoice-table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
        table.invoice-table th { text-align: left; padding-bottom: 8px; color: #2a3f7d; border-bottom: 2px solid #2a3f7d; }
        table.invoice-table td { padding: 8px 0; border-bottom: 1px solid #e0e0e0; }
        .totals { text-align: right; margin-top: 20px; font-size: 14px; }
        .total-remaining { background: #2a3f7d; color: white; padding: 10px; width: fit-content; margin-left: auto; border-radius: 4px; }
        .billing { margin-top: 30px; font-size: 14px; }
        .billing b { display: block; margin-bottom: 5px; }
    </style>
</head>
<body onload="window.print()">

<div class="invoice-container">
    <div class="invoice-header">
        <h1>INVOICE</h1>
        <div class="invoice-details d-flex">
            <div class="d-flex flex-column">
                Date: <b>{{ \Carbon\Carbon::now()->format('F d, Y') }}</b>
            </div>
            <br>
            |
            <div class="d-flex flex-column ms-3">
                Flight ID: <b>{{ $flightDetails->first()->id ?? 'N/A' }}</b>
            </div>
        </div>
    </div>

    <div class="company-info d-flex justify-content-between">
        <div>
            <b>Goflyhabibi</b><br>
            <i>Jobs fill your pocket, but adventures fill your soul.</i><br>
            <a href="https://Goflyhabibi.com">https://Goflyhabibi.com</a>
        </div>
        <div style="margin-top:10px;">
            Ticket Number:<br>
            Booking ID: {{ $bookingId ?? 'N/A' }}<br>
            Status: Paid<br>
            Service: Flight<br>
            Travel Type: {{ ucfirst(strtolower($type)) }}
        </div>
    </div>

    <table class="invoice-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Airline</th>
                <th>From</th>
                <th>To</th>
                <th>Departure Date</th>
                <th>Duration</th>
                <th>Total Fare (Including Meal & Baggage)</th>
            </tr>
        </thead>
        <tbody>
            {{-- Onward Flights --}}
            @foreach($flightDetails as $index => $flight)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $flight->airline_name ?? 'N/A' }}</td>
                <td>{{ $flight->departure_name ?? 'N/A' }} ({{ $flight->departure_city ?? 'N/A' }})</td>
                <td>{{ $flight->arrival_name ?? 'N/A' }} ({{ $flight->arrival_city ?? 'N/A' }})</td>
                <td>{{ \Carbon\Carbon::parse($flight->departure_time)->format('D, d M Y') }}</td>
                <td>{{ number_format($flight->duration / 60, 2) }}h</td>
                <td>₹ {{ $onward ?? 0}}</td>
            </tr>
            @endforeach

            {{-- Return Flights --}}
            @if($returnFlights->isNotEmpty())
                @foreach($returnFlights as $index => $return)
                <tr>
                    <td>{{ $flightDetails->count() + $index + 1 }}</td>
                    <td>{{ $return->airline_name ?? 'N/A' }}</td>
                    <td>{{ $return->departure_name ?? 'N/A' }} ({{ $return->departure_city ?? 'N/A' }})</td>
                    <td>{{ $return->arrival_name ?? 'N/A' }} ({{ $return->arrival_city ?? 'N/A' }})</td>
                    <td>{{ \Carbon\Carbon::parse($return->departure_time)->format('D, d M Y') }}</td>
                    <td>{{ number_format($return->duration / 60, 2) }}h</td>
                    <td>₹ {{  $returnFlight ?? 0}}</td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    {{-- Totals --}}
    <div class="totals">
        <div>Net total: ₹ {{ number_format($netPrice ?? 0, 0) }}</div>
        <div>Total paid: ₹ {{ number_format($netPrice ?? 0, 0) }}</div>
        <div class="total-remaining">Total Remaining: ₹ 0</div>
    </div>

    {{-- Billing Info --}}
    <div class="billing">
        <b>Billing to:</b>
        {{ $passengerDetails[0]['title'] ?? 'N/A' }} {{ $passengerDetails[0]['first_name'] ?? 'N/A' }} {{ $passengerDetails[0]['last_name'] ?? '' }}<br>
        {{ $contactDetails['email'] ?? 'N/A' }}<br>
        {{ $contactDetails['mobile'] ?? 'N/A' }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
