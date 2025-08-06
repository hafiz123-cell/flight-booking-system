@extends('layout.layout')
 

@section('content')
<div id="multicity-summary-display" style="display: none;">
  <div style="display:flex; justify-content:center;"></div>
</div>

<div id="oneway-summary-display" style="display: none;">
  <div style="display:flex; justify-content:center;"></div>
</div>

 <!-- Breadcrumb -->
    <section class="breadcrumb-outer text-center">
      <div class="container">
        <div class="breadcrumb-content">
          <h2 class="white">Flight List</h2>
          <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Flight</a></li>
              <li class="breadcrumb-item active" aria-current="page">Flight List</li>
            </ul>
          </nav>
        </div>
      </div>
      <div class="overlay"></div>
    </section>
   
    <!-- BreadCrumb Ends -->
   <section class="banner-form d-none form-style2" id="flight-add">
  <div class="container">
    <div class="form-content">
      <div class="price-navtab text-center">
        <ul class="nav nav-tabs">
          <li class="nav-item">
            <a class="nav-link active" id="rt" data-bs-toggle="tab" data-bs-target="#roundtrip">Round-Trip</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="ow" data-bs-toggle="tab" data-bs-target="#oneway">One-Way</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="mc" data-bs-toggle="tab" data-bs-target="#multicity">Multi-City</a>
          </li>
        </ul>
      </div>

      <div class="tab-content">  <!-- Close button -->
       
        <!-- Roundtrip Form -->
        <div class="tab-pane fade show active" id="roundtrip">
         
          <form id="searchFormRoundtrip" action="{{ route('flight.search.roundtrip') }}" method="get">


  <div id="roundtrip" class="tab-pane show active">
    <div class="row filter-box">
<button id="closeMulticityBtn"  class="closeBtn"
                style="position: absolute; top: 10px; width:60px; right: 10px; background: transparent; border: none; font-size: 24px; cursor: pointer;">
          &times;
        </button>
      <!-- Flying From -->
      <div class="col-lg-3 col-sm-12">
        <div class="form-group">
          <label>Flying From</label>
          <div class="input-box">
            <i class="flaticon-placeholder"></i>
            <div class="niceSelectWrapper input-group">
              <input type="hidden" name="from_where[]" id="from_where" />
              <input type="text" class="searchInput" placeholder="Where are you going?"
                onclick="toggleDropdown(this)" onkeyup="filterOptions(this)" data-hidden-id="from_where">
              <div class="optionsList" style="display: none;"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Flying To -->
      <div class="col-lg-3 col-sm-12">
        <div class="form-group">
          <label>Flying To</label>
          <div class="input-box">
            <i class="flaticon-placeholder"></i>
            <div class="niceSelectWrapper input-group">
              <input type="hidden" name="to_where[]" id="to_where" />
              <input type="text" class="searchInput" placeholder="Where are you going?"
                onclick="toggleDropdown(this)" onkeyup="filterOptions(this)" data-hidden-id="to_where">
              <div class="optionsList" style="display: none;"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Depart Date -->
      <div class="col-lg-3 col-sm-6">
        <div class="form-group">
          <label>Depart Date</label>
          <div class="input-box">
            <i class="flaticon-calendar"></i>
            <input id="date-range0" type="text" name="depart_date" placeholder="yyyy-mm-dd" />
          </div>
        </div>
      </div>

      <!-- Return Date -->
      <div class="col-lg-3 col-sm-6">
        <div class="form-group">
          <label>Return Date</label>
          <div class="input-box">
            <i class="flaticon-calendar"></i>
            <input id="date-range1" type="text" name="return_date" placeholder="yyyy-mm-dd" />
          </div>
        </div>
      </div>

      <!-- Travelers Dropdown -->
      <div class="col-lg-3 col-sm-6">
        <div class="form-group">
          <label>Travelers</label>
          <div class="input-box position-relative">
            <button class="btn dropdown-toggle w-100 text-start d-flex align-items-center" type="button"
              style="border: 1px solid #dee2e6;" id="travelerDropdown" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="flaticon-add-user mb-4"></i>
              <span id="travelerSummary" style="margin-left:20px; margin-top:5px;">2 Travelers - Economy</span>
            </button>

            <div class="dropdown-menu p-3" style="width: 100%; max-width: 350px;" aria-labelledby="travelerDropdown">
              <!-- Adults -->
              <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <div><strong>Adults</strong><br><small>Age 18–64</small></div>
                <div>
                  <button type="button" class="btn btn-outline-secondary btn-sm traveler-btn" data-type="adult" data-action="minus">-</button>
                  <span id="adultCount" class="mx-2">1</span>
                  <button type="button" class="btn btn-outline-secondary btn-sm traveler-btn" data-type="adult" data-action="plus">+</button>
                </div>
              </div>
              <!-- Children -->
              <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <div><strong>Children</strong><br><small>Age 3–17</small></div>
                <div>
                  <button type="button" class="btn btn-outline-secondary btn-sm traveler-btn" data-type="child" data-action="minus">-</button>
                  <span id="childCount" class="mx-2">1</span>
                  <button type="button" class="btn btn-outline-secondary btn-sm traveler-btn" data-type="child" data-action="plus">+</button>
                </div>
              </div>
              <!-- Infants -->
              <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <div><strong>Infants</strong><br><small>Age 0–2</small></div>
                <div>
                  <button type="button" class="btn btn-outline-secondary btn-sm traveler-btn" data-type="infant" data-action="minus">-</button>
                  <span id="infantCount" class="mx-2">0</span>
                  <button type="button" class="btn btn-outline-secondary btn-sm traveler-btn" data-type="infant" data-action="plus">+</button>
                </div>
              </div>
              <!-- Class -->
              <div class="form-group mt-3">
                <label for="travelClass">Class</label>
                <select id="travelClass" class="form-select" name="travel_class">
                  <option value="Economy" selected>Economy</option>
                  <option value="Business">Business</option>
                  <option value="First">First</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Hidden Inputs for Counts -->
      <input type="hidden" name="adults" id="adultsInput" value="1" />
      <input type="hidden" name="children" id="childrenInput" value="1" />
      <input type="hidden" name="infants" id="infantsInput" value="0" />

      <!-- Submit Button -->
      <div class="col-lg-3 col-sm-12">
        <div class="form-group mar-top-30">
          <button type="submit" class="biz-btn"><i class="fa fa-search"></i> Find Now</button>
        </div>
      </div>
    </div>
  </div>
</form>
        </div>

        <!-- Oneway Form -->
        <div class="tab-pane fade" id="oneway">
         
           <form id="searchFormOneway" name="tripjackOnewayForm" method="get" action="{{ route('tripjack.search') }}">
 

    
    <div class="row filter-box">
        <button id="closeMulticityBtn"  class="closeBtn"
                style="position: absolute; top: 10px; width:60px; right: 10px; background: transparent; border: none; font-size: 24px; cursor: pointer;">
          &times;
        </button>
      <!-- Flying From -->
      <div class="col-lg-3 col-sm-12">
        <div class="form-group">
          <label>Flying From</label>
          <div class="input-box">
            <i class="flaticon-placeholder"></i>
            <div class="niceSelectWrapper input-group">
              <input hidden name="from_where_oneway[]" id="from_where_oneway" />
              <input type="text" class="searchInput" name="from_where_text" placeholder="Where are you going?"
                onclick="toggleDropdown(this)"
                onkeyup="filterOptions(this)" data-hidden-id="from_where_oneway">
              <div class="optionsList" style="display: none;"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Flying To -->
      <div class="col-lg-3 col-sm-12">
        <div class="form-group">
          <label>Flying To</label>
          <div class="input-box">
            <i class="flaticon-placeholder"></i>
            <div class="niceSelectWrapper input-group">
              <input hidden name="to_where_oneway[]" id="to_where_oneway" />
              <input type="text" class="searchInput" name="to_where_text" placeholder="Where are you going?"
                onclick="toggleDropdown(this)"
                onkeyup="filterOptions(this)" data-hidden-id="to_where_oneway">
              <div class="optionsList" style="display: none;"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Depart Date -->
      <div class="col-lg-3 col-sm-12">
        <div class="form-group">
          <label>Depart Date</label>
          <div class="input-box">
            <i class="flaticon-calendar"></i>
            <input id="date-range0-oneway" name="departure_date_oneway" type="text" placeholder="yyyy-mm-dd" />
          </div>
        </div>
      </div>

      <!-- Travelers & Class Dropdown -->
      <div class="col-lg-3 col-sm-12">
        <div class="form-group">
          <label>Travelers</label>
          <div class="input-box position-relative">
            <button class="btn dropdown-toggle w-100 text-start d-flex align-items-center" type="button" style="border: 1px solid #dee2e6;" id="travelerDropdownOneway" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="flaticon-add-user mb-4"></i>
              <span id="travelerSummaryOneway" style="margin-left:20px; margin-top:5px;">1 Adult - Economy</span>
            </button>

            <div class="dropdown-menu p-3" style="width: 100%; max-width: 350px;" aria-labelledby="travelerDropdownOneway">

              <!-- Adults -->
              <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <div><strong>Adults</strong><br><small>Age 18–64</small></div>
                <div>
                  <button type="button" class="btn btn-outline-secondary btn-sm traveler-btn" data-type="adult" data-action="minus" data-context="oneway">-</button>
                  <span id="adultCountOneway" class="mx-2">1</span>
                  <input type="hidden" name="adult_count_oneway" id="adultCountInputOneway" value="1">
                  <button type="button" class="btn btn-outline-secondary btn-sm traveler-btn" data-type="adult" data-action="plus" data-context="oneway">+</button>
                </div>
              </div>

              <!-- Children -->
              <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <div><strong>Children</strong><br><small>Age 3–17</small></div>
                <div>
                  <button type="button"   class="btn btn-outline-secondary btn-sm traveler-btn" data-type="child" data-action="minus" data-context="oneway">-</button>
                  <span id="childCountOneway" class="mx-2">0</span>
                  <input type="hidden" name="child_count_oneway" id="childCountInputOneway" value="0">
                  <button type="button" class="btn btn-outline-secondary btn-sm traveler-btn" data-type="child" data-action="plus" data-context="oneway">+</button>
                </div>
              </div>

              <!-- Infants -->
              <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <div><strong>Infants</strong><br><small>Age 0–2</small></div>
                <div>
                  <button type="button" class="btn btn-outline-secondary btn-sm traveler-btn" data-type="infant" data-action="minus" data-context="oneway">-</button>
                  <span id="infantCountOneway" class="mx-2">0</span>
                  <input type="hidden" name="infant_count_oneway" id="infantCountInputOneway" value="0">
                  <button  type="button" class="btn btn-outline-secondary btn-sm traveler-btn" data-type="infant" data-action="plus" data-context="oneway">+</button>
                </div>
              </div>

              <!-- Class Selection -->
              <div class="form-group mt-3">
                <label for="travelClassOneway">Class</label>
                <select id="travelClassOneway" class="form-select" name="travel_class_oneway">
                  <option value="Economy" selected>Economy</option>
                  <option value="Business">Business</option>
                  <option value="First">First</option>
                </select>
              </div>

            </div>
          </div>
        </div>
      </div>

      <!-- Hidden Summary Field -->
      <input type="hidden" name="travel_summary_oneway" id="travelSummaryInputOneway">

      <!-- Search Button -->
      <div class="col-lg-3 col-sm-12">
        <div class="form-group mar-top-30">
          <button type="submit" class="biz-btn" id="findFlightBtn"><i class="fa fa-search"></i> Find Now</button>
        </div>
      </div>
    </div>
  
</form>
        </div>

        <!-- Multicity Form -->
       
  <div class="tab-pane fade" id="multicity">
     
  <form id="searchFormMulticityUnique">

    <!-- Start Segment Template -->
    <div class="row filter-box segment-wrapper-multicity">
       <button id="closeMulticityBtn"  class="closeBtn"
                style="position: absolute; width:60px; top: 10px; right: 10px; background: transparent; border: none; font-size: 24px; cursor: pointer;">
          &times;
        </button>

      <div class="col-lg-3">
        <div class="form-group">
          <label>Flying From</label>
          <div class="input-box">
            <i class="flaticon-placeholder"></i>
            <div class="niceSelectWrapper input-group">
              <input hidden  value="" name="from_where_multicity_unique[]" id="from_where_multicity_unique">
              <input type="text" value="" class="searchInput" name="from_where_text_multicity[]" placeholder="From"
                onclick="toggleDropdown(this)" onkeyup="filterOptions(this)" data-hidden-id="from_where_multicity_unique">
              <div class="optionsList" style="display: none;"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3">
        <div class="form-group">
          <label>Flying To</label>
          <div class="input-box">
            <i class="flaticon-placeholder"></i>
            <div class="niceSelectWrapper input-group">
              <input hidden value="" name="to_where_multicity_unique[]" id="to_where_multicity_unique">
              <input type="text" value="" class="searchInput" name="to_where_text_multicity[]" placeholder="To"
                onclick="toggleDropdown(this)" onkeyup="filterOptions(this)" data-hidden-id="to_where_multicity_unique">
              <div class="optionsList" style="display: none;"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-sm-12">
        <div class="form-group">
          <label>Depart Date</label>
          <div class="input-box">
            <i class="flaticon-calendar"></i>
            <input name="departure_date_multicity_unique[]" class="departure_date_multicity_unique" type="text" class="form-control" placeholder="yyyy-mm-dd">
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-sm-12">
        <div class="form-group">
          <label>Travelers</label>
          <div class="input-box position-relative">
            <button class="btn dropdown-toggle w-100 text-start d-flex align-items-center" type="button"
              style="border: 1px solid #dee2e6;" id="travelerDropdownMulticityUnique" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="flaticon-add-user mb-4"></i>
              <span id="travelerSummaryMulticityUnique" style="margin-left:20px; margin-top:5px;">1 Adult - Economy</span>
            </button>
            <div class="dropdown-menu p-3" style="width: 100%; max-width: 350px;" aria-labelledby="travelerDropdownMulticityUnique">
              <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <div><strong>Adults</strong><br><small>Age 18–64</small></div>
                <div>
                  <button type="button" class="btn btn-outline-secondary btn-sm traveler-btn" data-type="adult" data-action="minus" data-context="multicity_unique">-</button>
                  <span id="adultCountMulticityUnique" class="mx-2">1</span>
                  <input type="hidden" name="adult_count_multicity_unique" id="adultCountInputMulticityUnique" value="1">
                  <button type="button" class="btn btn-outline-secondary btn-sm traveler-btn" data-type="adult" data-action="plus" data-context="multicity_unique">+</button>
                </div>
              </div>

              <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <div><strong>Children</strong><br><small>Age 3–17</small></div>
                <div>
                  <button type="button" class="btn btn-outline-secondary btn-sm traveler-btn" data-type="child" data-action="minus" data-context="multicity_unique">-</button>
                  <span id="childCountMulticityUnique" class="mx-2">0</span>
                  <input type="hidden" name="child_count_multicity_unique" id="childCountInputMulticityUnique" value="0">
                  <button type="button" class="btn btn-outline-secondary btn-sm traveler-btn" data-type="child" data-action="plus" data-context="multicity_unique">+</button>
                </div>
              </div>

              <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <div><strong>Infants</strong><br><small>Age 0–2</small></div>
                <div>
                  <button type="button" class="btn btn-outline-secondary btn-sm traveler-btn" data-type="infant" data-action="minus" data-context="multicity_unique">-</button>
                  <span id="infantCountMulticityUnique" class="mx-2">0</span>
                  <input type="hidden" name="infant_count_multicity_unique" id="infantCountInputMulticityUnique" value="0">
                  <button type="button" class="btn btn-outline-secondary btn-sm traveler-btn" data-type="infant" data-action="plus" data-context="multicity_unique">+</button>
                </div>
              </div>

              <div class="form-group mt-3">
                <label for="travelClassMulticityUnique">Class</label>
                <select id="travelClassMulticityUnique" class="form-select" name="travel_class_multicity_unique">
                  <option value="Economy" selected>Economy</option>
                  <option value="Business">Business</option>
                  <option value="First">First</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-sm-12">
        <div class="form-group mar-top-30">
          <button type="submit" class="biz-btn" id="multicityFindFlightBtnUnique"><i class="fa fa-search"></i> Find Now</button>
        </div>
      </div>
    

  <!-- Second Segment -->
<div class="row mt-4 city-segment segment-wrapper-multicity">

  <!-- Flying From -->
  <div class=" col-sm-12" style="width:215px;">
    <div class="form-group">
      <label>Flying From</label>
      <div class="input-box">
        <i class="flaticon-placeholder"></i>
        <div class="niceSelectWrapper input-group">
          <input hidden value="" name="from_where_multicity_unique[1]" id="from_where_multicity_unique1">
          <input type="text" value="" class="searchInput form-control" name="from_where_text_multicity[1]"
            placeholder="From" onclick="toggleDropdown(this)" onkeyup="filterOptions(this)"
            data-hidden-id="from_where_multicity_unique1">
          <div class="optionsList" style="display: none;"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Flying To -->
  <div class="col-sm-12" style="width:215px;">
    <div class="form-group">
      <label>Flying To</label>
      <div class="input-box">
        <i class="flaticon-placeholder"></i>
        <div class="niceSelectWrapper input-group">
          <input hidden value="" name="to_where_multicity_unique[1]" id="to_where_multicity_unique1">
          <input type="text" value="" class="searchInput form-control" name="to_where_text_multicity[1]"
            placeholder="To" onclick="toggleDropdown(this)" onkeyup="filterOptions(this)"
            data-hidden-id="to_where_multicity_unique1">
          <div class="optionsList" style="display: none;"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Depart Date -->
  <div class="col-sm-12" style="width:215px;">
    <div class="form-group">
      <label>Depart Date</label>
      <div class="input-box">
        <i class="flaticon-calendar"></i>
        <input name="departure_date_multicity_unique[]" class="departure_date_multicity_unique1" type="text" class="form-control"
          placeholder="yyyy-mm-dd">
      </div>
    </div>
  </div>

 
<!-- Segment Actions (Add/Remove buttons go here) -->
<div class="col-lg-3 col-sm-12 segment-action d-flex">
  <button type="button" class="biz-btn add-segment-btn w-60" style="margin-top: 35px; width:130px;" onclick="addSegmentRow()">
    +Add More
  </button>
</div>



    <input type="hidden" name="travel_summary_multicity_unique" id="travelSummaryInputMulticityUnique">
  </form>
</div>
</div>

      </div>
    </div>
  </div>
</section>
    <!-- flight-list starts -->

    <section class="list flight-list">
      <div class="container">
        <div class="row">
          <div class="col-lg-8">
            <div class="content">
             
              <div class="flight-full">
                <div class="item mar-bottom-30">

    @php
        use Illuminate\Support\Str;
        use Carbon\Carbon;

    $segments = $result['segments'] ?? [];
    $priceData = $result['price']['fd'] ?? [];
    @endphp
      @if(isset($results['searchResult']['tripInfos']['ONWARD']))
    @if(isset($results['searchResult']['tripInfos']['ONWARD']) && count($results['searchResult']['tripInfos']['ONWARD']) > 0)
       @foreach($results['searchResult']['tripInfos']['ONWARD'] as $index => $segment)
    @php
        $flightList = $segment['sI'];
        $firstFlight = $flightList[0];
        $lastFlight = end($flightList);

        $departureTime = \Carbon\Carbon::parse($firstFlight['dt']);
        $arrivalTime = \Carbon\Carbon::parse($lastFlight['at']);
        $durationMinutes = $arrivalTime->diffInMinutes($departureTime);
        $hours = intdiv($durationMinutes, 60);
        $minutes = $durationMinutes % 60;

        // Stopover Info
        $stopovers = [];
        foreach ($flightList as $leg) {
            if (($leg['sN'] ?? 0) === 1) {
                $stopovers[] = $leg['da'];
            }
        }

        $stopCount = count($stopovers);
        $stopLabel = $stopCount === 0 ? 'Non-stop' : "{$stopCount} stop" . ($stopCount > 1 ? 's' : '');

        // Airline info
        $airlineCode = $firstFlight['fD']['aI']['code'] ?? 'XX';
        $airlineName = $firstFlight['fD']['aI']['name'] ?? 'Unknown Airline';
        $flightNumber = $firstFlight['fD']['fN'] ?? 'XXX';

        $fromCity = $firstFlight['da']['city'] ?? '';
        $toCity = $lastFlight['aa']['city'] ?? '';
        $fromTime = $departureTime->format('H:i');
        $toTime = $arrivalTime->format('H:i');
        $fromDate = $departureTime->format('d M');
        $toDate = $arrivalTime->format('d M');

        $logoPath = public_path("AirlinesLogo/{$airlineCode}.png");
        $logoUrl = file_exists($logoPath)
            ? asset("AirlinesLogo/{$airlineCode}.png")
            : asset("AirlinesLogo/default.png");

        $priceList = $segment['totalPriceList'] ?? [];

        $uniqueId = 'segment_' . $index;
    @endphp

    <div class="flight-card mb-4 p-3 border rounded shadow-sm bg-white">
        <div class="row align-items-center">
            <div class="col-md-2 text-center">
                <img src="{{ $logoUrl }}" class="img-fluid" style="max-height: 40px;">
                <p class="mt-2 mb-0 fw-bold text-dark">{{ $airlineName }}</p>
                <small>{{ $flightNumber }}</small>
            </div>

            <div class="col-md-2 text-center">
                <small class="text-muted">{{ $fromCity }}</small><br>
                <h5 class="mb-1 fw-bold">{{ $fromTime }}</h5>
                <small class="text-muted">{{ $fromDate }}</small>
            </div>

            <div class="col-md-2 text-center">
                <span class="d-block fw-bold">{{ $hours }}h {{ $minutes }}m</span>
                <div style="width: 100%; height: 2px; background-color: orange; position: relative; margin: 6px 0;">
                    <span style="position: absolute; right: -8px; top: -6px; color: orange; font-size: 18px;">→</span>
                </div>
                <small class="text-muted">{{ $stopLabel }}</small>
            </div>

            <div class="col-md-2 text-center">
                <small class="text-muted">{{ $toCity }}</small><br>
                <h5 class="mb-1 fw-bold">{{ $toTime }}</h5>
                <small class="text-muted">{{ $toDate }}</small>
            </div>

                        @php 
    $priceList = $priceList ?? []; 
    $containerId = 'fareBlock_' . $index; // Unique container ID
    $fareCount = count($priceList); 
@endphp
<div class="col-md-2 text-start" id="{{ $containerId }}">
    @foreach($priceList as $loopIndex => $priceItem)
        @php
            $totalFare = $priceItem['fd']['ADULT']['fC']['TF'] ?? 0;
            $tag = $priceItem['fareIdentifier'] ?? 'Standard';
            $cabinClassRaw = $priceItem['fd']['ADULT']['cc'] ?? 'N/A';
            $cabinClass = \Illuminate\Support\Str::title(strtolower($cabinClassRaw)); // Economy, Business, etc.
            $isHidden = $loopIndex >= 3 ? 'd-none' : '';
        @endphp

        <div 
            class="mb-2 p-2 more-fare {{ $isHidden }}" 
            data-fare-index="{{ $loopIndex }}"
        >
               
            {{-- Fare info --}} 
            <div class="d-flex">
             <input class="form-check-input" type="radio">
            <p class="mb-1">₹{{ number_format($totalFare, 2) }}</p>
            </div>
            {{-- Tag and Cabin class --}}
            <div class="d-flex align-items-center justify-content-between">
                <span class="badge bg-warning text-white text-uppercase">{{ $tag }}</span>
                <small class="text-muted">{{ $cabinClass }}</small>
            </div>
        </div>
    @endforeach

    @if($fareCount > 3)
        <button class="btn p-1" style="border:1px solid #ccc; font-size:12px; color:orange;" onclick="toggleMoreFares('{{ $containerId }}', this)">
            +More Fares
        </button>
    @endif
</div>



            <div class="col-md-2 text-center">
            @php
    $itineraryId = $priceItem['id'] ?? '';
    $fareIdentifier = $priceItem['fareIdentifier'] ?? '';
    $bookUrl = route('redirect.booking', [
        'itineraryId' => $itineraryId,
        'fareIdentifier' => $fareIdentifier
    ]);
@endphp

<a href="{{ $bookUrl }}" class="btn btn-sm w-100 text-white" style="background-color: orange;">Book</a>

            </div>
        </div>

        <!-- View Details Button -->
    <div class="mt-3">
    <!-- Button -->
    <button type="button" id="view" class="btn btn-sm" style="background-color: #f5f5f5; color: rgb(255, 138, 5)" onclick="toggleDetails(this)">
        View Details +
    </button>

    <!-- Hidden Content: FLIGHT DETAILS -->
    <div class="flight-details-content mt-2" style="display: none;">

        <!-- TABS -->
        <ul class="segment-subtabs d-flex list-unstyled mb-0 border-bottom">
           <li class="tab-items active px-3 py-2" style="cursor: pointer;" onclick="switchFlightTab(this, 'flightDetailsOneway_{{ $uniqueId }}')">
    <small>Flight Details</small>
</li>
<li class="tab-items px-3 py-2" style="cursor: pointer;" onclick="switchFlightTab(this, 'fareDetailsOneway_{{ $uniqueId }}')">
    <small>Fare Details</small>
</li>
<li class="tab-items px-3 py-2" style="cursor: pointer;" onclick="switchFlightTab(this, 'fareRulesOneway_{{ $uniqueId }}')">
    <small>Fare Rules</small>
</li>
<li class="tab-items px-3 py-2" style="cursor: pointer;" onclick="switchFlightTab(this, 'baggageInfoOneway_{{ $uniqueId }}')">
    <small>Baggage Info</small>
</li>

        </ul>

        <!-- Flight Details Tab -->
        <div id="flightDetailsOneway_{{ $uniqueId }}" class="tab-subcontent bg-white p-3 shadow-sm rounded" style="position: relative;">
            <button onclick="closeDetails(this)" style="position: absolute; top: 10px; right: 10px; background: none; border: none; font-size: 16px;">✖</button>

            @php
                $firstSeg = $flightList[0];
                $lastSeg = end($flightList);

                $fromCity = $firstSeg['da']['city'] ?? '';
                $fromCode = $firstSeg['da']['code'] ?? '';
                $toCity = $lastSeg['aa']['city'] ?? '';
                $toCode = $lastSeg['aa']['code'] ?? '';
                $departureDateTime = \Carbon\Carbon::parse($firstSeg['dt'])->format('D, d M Y');
            @endphp

            <div class="mb-4 border-bottom pb-3 d-flex">
                <h5 class="fw-bold mb-1 text-dark">
                    {{ $fromCity }} → {{ $toCity }}
                </h5>
                <small class="text-muted ms-2 mt-1">{{ $departureDateTime }}</small>
            </div>

            @foreach ($flightList as $segData)
                @php
                    $airlineCode = $segData['fD']['aI']['code'] ?? 'XX';
                    $fullFlightCode = $segData['fD']['fN'] ?? 'XX-0000';
                    $logoPath = public_path("AirlinesLogo/$airlineCode.png");
                    $logoUrl = file_exists($logoPath) ? asset("AirlinesLogo/$airlineCode.png") : asset("AirlinesLogo/default.png");

                    $depTime = \Carbon\Carbon::parse($segData['dt'])->format('d M, D, H:i');
                    $arrTime = \Carbon\Carbon::parse($segData['at'])->format('M d, D, H:i');
                    $depCity = $segData['da']['city'] ?? '';
                    $arrCity = $segData['aa']['city'] ?? '';
                    $depAirportName = $segData['da']['name'] ?? '';
                    $arrAirportName = $segData['aa']['name'] ?? '';
                    $durationMin = \Carbon\Carbon::parse($segData['at'])->diffInMinutes(\Carbon\Carbon::parse($segData['dt']));
                    $durationText = floor($durationMin / 60) . 'h ' . str_pad($durationMin % 60, 2, '0', STR_PAD_LEFT) . 'm';
                    $classCode = $priceData['ADULT']['cc'] ?? 'ECONOMY';
                    $className = \Illuminate\Support\Str::title(strtolower($classCode));
                @endphp

                <div class="row mb-3 border-bottom pb-2 align-items-center">
                    <div class="col-md-3 d-flex align-items-center justify-content-center">
                        <img src="{{ $logoUrl }}" alt="{{ $airlineCode }}" style="max-height: 24px;" class="me-2">
                        <div class="mt-2">
                            <small class="text-muted text-dark">{{ $airlineCode }}-{{ $fullFlightCode }}</small>
                            <p class="text-muted">{{ $className }}</p>
                        </div>
                    </div>

                    <div class="col-md-3 text-center">
                        <small class="fw-bold">{{ $depTime }}</small><br>
                        <small>{{ $depCity }}</small><br>
                        <small class="text-muted">{{ $depAirportName }}</small>
                    </div>

                    <div class="col-md-2 text-center">
                        <small class="text-muted">Non-stop</small>
                        <div style="width: 100%; height: 2px; background-color: orange; position: relative; margin: 6px 0;">
                            <span style="position: absolute; right: -8px; top: -6px; color: orange; font-size: 18px;">→</span>
                        </div>
                        <div class="my-1 text-muted small">{{ $durationText }}</div>
                    </div>

                    <div class="col-md-3 text-center">
                        <small class="fw-bold">{{ $arrTime }}</small><br>
                        <small>{{ $arrCity }}</small><br>
                        <small class="text-muted">{{ $arrAirportName }}</small>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Fare Details Tab -->
       <!-- Fare Details Tab -->
<div id="fareDetailsOneway_{{ $uniqueId }}" class="tab-subcontent bg-white p-3 shadow-sm rounded d-none" style="position: relative;">
    <button onclick="closeDetails(this)" style="position: absolute; top: 10px; right: 10px; border: none; background: none; font-size: 16px; cursor: pointer;">✖</button>

    @php
        $adultCount = request()->get('adult_count', 1);
        $childCount = request()->get('child_count', 0);
        $infantCount = request()->get('infant_count', 0);

        $tripInfos = $results['searchResult']['tripInfos']['ONWARD'] ?? [];
        $flightData = $tripInfos[0] ?? [];
        $fare = $flightData['totalPriceList'][0]['fd'] ?? [];

        // Adult fares
        $adultBase = $fare['ADULT']['fC']['BF'] ?? 0;
        $adultTax  = ($fare['ADULT']['fC']['TF'] ?? 0) - $adultBase;
        $totalAdultBase = $adultBase * $adultCount;
        $totalAdultTax  = $adultTax * $adultCount;

        // Tax breakdown
        $adultAfC = $fare['ADULT']['afC']['TAF'] ?? [];
        $managementFee = ($adultAfC['MF'] ?? 0);
        $mfTax         = ($adultAfC['MFT'] ?? 0);
        $yq            = ($adultAfC['YR'] ?? 0);
        $otherTaxes    = ($adultAfC['OT'] ?? 0);

        // Child fares
        $childBase = $fare['CHILD']['fC']['BF'] ?? 0;
        $childTax  = ($fare['CHILD']['fC']['TF'] ?? 0) - $childBase;
        $totalChildBase = $childBase * $childCount;
        $totalChildTax  = $childTax * $childCount;

        // Infant fares
        $infantBase = $fare['INFANT']['fC']['BF'] ?? 0;
        $infantTax  = ($fare['INFANT']['fC']['TF'] ?? 0) - $infantBase;
        $totalInfantBase = $infantBase * $infantCount;
        $totalInfantTax  = $infantTax * $infantCount;

        $grandTotal = $totalAdultBase + $totalAdultTax + $totalChildBase + $totalChildTax + $totalInfantBase + $totalInfantTax;
    @endphp

    <table class="table border-0" style="border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 1px solid #eee;">
                <th class="border-0">TYPE</th>
                <th class="border-0">Fare</th>
                <th class="border-0">Total</th>
            </tr>
        </thead>
        <tbody>
            {{-- Adult --}}
            @if ($adultCount > 0)
                <tr><td colspan="3" style="color:#999;">Fare Details for Adult</td></tr>
                <tr>
                    <td>Base Price</td>
                    <td>₹{{ number_format($adultBase, 2) }} x {{ $adultCount }}</td>
                    <td>₹{{ number_format($totalAdultBase, 2) }}</td>
                </tr>
                <tr>
                    <td>
                        Taxes and fees
                        <i style="color:orange; font-weight:bold;"
                           data-bs-toggle="tooltip"
                           data-bs-placement="top"
                           data-bs-html="true"
                           title="Management Fee: ₹{{ number_format($managementFee, 2) }}<br>
                                  Management Fee Tax: ₹{{ number_format($mfTax, 2) }}<br>
                                  YQ: ₹{{ number_format($yq, 2) }}<br>
                                  Other Taxes: ₹{{ number_format($otherTaxes, 2) }}">
                            i
                        </i>
                    </td>
                    <td>₹{{ number_format($adultTax, 2) }} x {{ $adultCount }}</td>
                    <td>₹{{ number_format($totalAdultTax, 2) }}</td>
                </tr>
            @endif

            {{-- Child --}}
            @if ($childCount > 0)
                <tr><td colspan="3" style="color:#999;">Fare Details for Child</td></tr>
                <tr>
                    <td>Base Price</td>
                    <td>₹{{ number_format($childBase, 2) }} x {{ $childCount }}</td>
                    <td>₹{{ number_format($totalChildBase, 2) }}</td>
                </tr>
                <tr>
                    <td>
                        Taxes and fees
                        <i style="color:orange; font-weight:bold;"
                           data-bs-toggle="tooltip"
                           data-bs-placement="top"
                           data-bs-html="true"
                           title="Management Fee: ₹{{ number_format($managementFee, 2) }}<br>
                                  Management Fee Tax: ₹{{ number_format($mfTax, 2) }}<br>
                                  YQ: ₹{{ number_format($yq, 2) }}<br>
                                  Other Taxes: ₹{{ number_format($otherTaxes, 2) }}">
                            i
                        </i>
                    </td>
                    <td>₹{{ number_format($childTax, 2) }} x {{ $childCount }}</td>
                    <td>₹{{ number_format($totalChildTax, 2) }}</td>
                </tr>
            @endif

            {{-- Infant --}}
            @if ($infantCount > 0)
                <tr><td colspan="3" style="color:#999;">Fare Details for Infant</td></tr>
                <tr>
                    <td>Base Price</td>
                    <td>₹{{ number_format($infantBase, 2) }} x {{ $infantCount }}</td>
                    <td>₹{{ number_format($totalInfantBase, 2) }}</td>
                </tr>
                <tr>
                    <td>
                        Taxes and fees
                        <i style="color:orange; font-weight:bold;"
                           data-bs-toggle="tooltip"
                           data-bs-placement="top"
                           data-bs-html="true"
                           title="Management Fee: ₹{{ number_format($managementFee, 2) }}<br>
                                  Management Fee Tax: ₹{{ number_format($mfTax, 2) }}<br>
                                  YQ: ₹{{ number_format($yq, 2) }}<br>
                                  Other Taxes: ₹{{ number_format($otherTaxes, 2) }}">
                            i
                        </i>
                    </td>
                    <td>₹{{ number_format($infantTax, 2) }} x {{ $infantCount }}</td>
                    <td>₹{{ number_format($totalInfantTax, 2) }}</td>
                </tr>
            @endif

            {{-- Total --}}
            <tr style="border-top: 1px solid #eee;">
                <td colspan="2"><strong>Total</strong></td>
                <td><strong>₹{{ number_format($grandTotal, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>
</div>


        <!-- Fare Rules Tab -->
        <div id="fareRulesOneway_{{ $uniqueId }}" class="tab-subcontent bg-white p-3 shadow-sm rounded d-none" style="position: relative;">
            <button onclick="closeDetails(this)" style="position: absolute; top: 10px; right: 10px; background: none; border: none; font-size: 16px;">✖</button>
            <p>Tickets are non-refundable. Change fee applicable.</p>
        </div>

        <!-- Baggage Info Tab -->
       <div id="baggageInfoOneway_{{ $uniqueId }}" class="tab-subcontent bg-white p-3 shadow-sm rounded d-none" style="position: relative;">
    <button onclick="closeDetails(this)" style="position: absolute; top: 10px; right: 10px; background: none; border: none; font-size: 16px;">✖</button>

    @php
        $firstSeg = $flightList[0] ?? [];
        $lastSeg = end($flightList) ?? [];

        $from = $firstSeg['da']['code'] ?? 'N/A';
        $to = $lastSeg['aa']['code'] ?? 'N/A';
        $sector = "$from-$to";

        $totalPriceList = $price['totalPriceList'] ?? [];
    @endphp

    <h6 class="mb-3">Baggage Allowance (Sector: {{ $sector }})</h6>

    @if(count($totalPriceList))
    <table class="table border-0" style="border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 1px solid #eee;">
                <th>Fare Type</th>
                <th>Check-in Baggage</th>
                <th>Cabin Baggage</th>
            </tr>
        </thead>
        <tbody>
            @foreach($totalPriceList as $fare)
                @php
                    $fareType = $fare['fareIdentifier'] ?? 'N/A';
                    $adult = $fare['fd']['ADULT'] ?? [];
                    $checkIn = $adult['bI']['iB'] ?? '0 Kg';
                    $cabin = $adult['bI']['cB'] ?? '0 Kg';
                @endphp
                <tr>
                    <td>{{ $fareType }}</td>
                    <td>{{ $checkIn }}</td>
                    <td>{{ $cabin }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p>No baggage information available.</p>
    @endif
</div>



    </div>

    <!-- SEAT INFO -->
    <br>
    <span style="margin-left:20px; color: rgb(255, 138, 5); font-size:12px;">7 Seats Left</span>
</div>


    </div>
@endforeach

    @else
        <p>No flights found.</p>
    @endif
     @endif

    {{-- roundtrip --}}
@php
    $onwardFlights = $resultsRound['searchResult']['tripInfos']['ONWARD'] ?? [];
    $returnFlights = $resultsRound['searchResult']['tripInfos']['RETURN'] ?? [];

    $maxPairs = min(count($onwardFlights), count($returnFlights)); // Pair onward & return flights
    $segments = $result['segments'] ?? [];
    $priceData = $result['price']['fd'] ?? [];
@endphp
<div class="row">
    <div class="col-md-6 m-0 p-0">
      @if(isset($resultsRound['searchResult']['tripInfos']['ONWARD']) && count($resultsRound['searchResult']['tripInfos']['ONWARD']) > 0)
    @foreach($resultsRound['searchResult']['tripInfos']['ONWARD'] as $index => $segment)
        @php
            $flightList = $segment['sI'];
            $firstFlight = $flightList[0];
            $lastFlight = end($flightList);

            $departureTime = Carbon::parse($firstFlight['dt']);
            $arrivalTime = Carbon::parse($lastFlight['at']);
            $durationMinutes = $arrivalTime->diffInMinutes($departureTime);
            $hours = intdiv($durationMinutes, 60);
            $minutes = $durationMinutes % 60;

            $stopovers = [];
            foreach ($flightList as $leg) {
                if (($leg['sN'] ?? 0) === 1) {
                    $stopovers[] = $leg['da'];
                }
            }

            $stopCount = count($stopovers);
            $stopLabel = $stopCount === 0 ? 'Non-stop' : "{$stopCount} stop" . ($stopCount > 1 ? 's' : '');

            $airlineCode = $firstFlight['fD']['aI']['code'] ?? 'XX';
            $airlineName = $firstFlight['fD']['aI']['name'] ?? 'Unknown Airline';
            $flightNumber = $firstFlight['fD']['fN'] ?? 'XXX';

            $fromCity = $firstFlight['da']['city'] ?? '';
            $toCity = $lastFlight['aa']['city'] ?? '';
            $fromTime = $departureTime->format('H:i');
            $toTime = $arrivalTime->format('H:i');
            $fromDate = $departureTime->format('d M');
            $toDate = $arrivalTime->format('d M');

            $logoPath = public_path("AirlinesLogo/{$airlineCode}.png");
            $logoUrl = file_exists($logoPath)
                ? asset("AirlinesLogo/{$airlineCode}.png")
                : asset("AirlinesLogo/default.png");

            $priceList = $segment['totalPriceList'] ?? [];
            $uniqueId = 'segment_' . $index;
        @endphp

        <div class="flight-card mb-4 p-3 border rounded shadow-sm bg-white">
            <div class="row align-items-center">
                <!-- Airline Info -->
                <div class="col-md-2 text-center">
                    <img src="{{ $logoUrl }}" class="img-fluid" style="max-height: 40px;">
                    <p class="mt-2 mb-0 fw-bold text-dark">{{ $airlineName }}</p>
                    <small>{{ $flightNumber }}</small>
                </div>

                <!-- Departure Info -->
                <div class="col-md-2 text-center">
                    <small class="text-muted">{{ $fromCity }}</small><br>
                    <h5 class="mb-1 fw-bold">{{ $fromTime }}</h5>
                    <small class="text-muted">{{ $fromDate }}</small>
                </div>

                <!-- Duration -->
                <div class="col-md-2 text-center">
                    <span class="d-block fw-bold">{{ $hours }}h {{ $minutes }}m</span>
                    <div style="width: 100%; height: 2px; background-color: orange; position: relative; margin: 6px 0;">
                        <span style="position: absolute; right: -8px; top: -6px; color: orange; font-size: 18px;">→</span>
                    </div>
                    <small class="text-muted">{{ $stopLabel }}</small>
                </div>

                <!-- Arrival Info -->
                <div class="col-md-2 text-center">
                    <small class="text-muted">{{ $toCity }}</small><br>
                    <h5 class="mb-1 fw-bold">{{ $toTime }}</h5>
                    <small class="text-muted">{{ $toDate }}</small>
                </div>

                <!-- Fare Options -->
                @php 
                    $containerId = 'fareBlock_' . $index;
                    $fareCount = count($priceList);
                @endphp
                <div class="col-md-2 text-start" id="{{ $containerId }}">
                    @foreach($priceList as $loopIndex => $priceItem)
                        @php
                            $totalFare = $priceItem['fd']['ADULT']['fC']['TF'] ?? 0;
                            $tag = $priceItem['fareIdentifier'] ?? 'Standard';
                            $cabinClassRaw = $priceItem['fd']['ADULT']['cc'] ?? 'N/A';
                            $cabinClass = Str::title(strtolower($cabinClassRaw));
                            $isHidden = $loopIndex >= 3 ? 'd-none' : '';
                        @endphp

                        <div class="mb-2 p-2 more-fare {{ $isHidden }}" data-fare-index="{{ $loopIndex }}">
                            <div class="d-flex">
                                <input class="form-check-input" type="radio">
                                <p class="mb-1">₹{{ number_format($totalFare, 2) }}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="badge bg-warning text-white text-uppercase">{{ $tag }}</span>
                                <small class="text-muted">{{ $cabinClass }}</small>
                            </div>
                        </div>
                    @endforeach

                    @if($fareCount > 3)
                        <button class="btn p-1" style="border:1px solid #ccc; font-size:12px; color:orange;" onclick="toggleMoreFares('{{ $containerId }}', this)">+More Fares</button>
                    @endif
                </div>

                <!-- Book Button -->
                {{-- <div class="col-md-2 text-center">
                    @php
                        $itineraryId = $priceItem['id'] ?? '';
                        $fareIdentifier = $priceItem['fareIdentifier'] ?? '';
                        $bookUrl = route('redirect.booking', [
                            'itineraryId' => $itineraryId,
                            'fareIdentifier' => $fareIdentifier
                        ]);
                    @endphp
                    <a href="{{ $bookUrl }}" class="btn btn-sm w-100 text-white" style="background-color: orange;">Book</a>
                </div> --}}
            </div>

            <!-- View Details Button -->
            <div class="mt-3">
                <button type="button" class="btn btn-sm" style="background-color: #f5f5f5; color: rgb(255, 138, 5)" onclick="toggleDetails(this)">View Details +</button>
                <div class="flight-details-content mt-2" style="display: none;">

                    <!-- Tabs -->
                    <ul class="segment-subtabs d-flex list-unstyled mb-0 border-bottom">
                        <li class="tab-items active px-3 py-2" style="cursor: pointer;" onclick="switchFlightTab(this, 'flightDetailsOneway_{{ $uniqueId }}')"><small>Flight Details</small></li>
                        <li class="tab-items px-3 py-2" style="cursor: pointer;" onclick="switchFlightTab(this, 'fareDetailsOneway_{{ $uniqueId }}')"><small>Fare Details</small></li>
                        <li class="tab-items px-3 py-2" style="cursor: pointer;" onclick="switchFlightTab(this, 'fareRulesOneway_{{ $uniqueId }}')"><small>Fare Rules <i class="bi bi-info-circle" data-bs-toggle="tooltip" title="View Fare Rules"></i></small></li> <!-- Added "i" icon -->
                        <li class="tab-items px-3 py-2" style="cursor: pointer;" onclick="switchFlightTab(this, 'baggageInfoOneway_{{ $uniqueId }}')"><small>Baggage Info <i class="bi bi-info-circle" data-bs-toggle="tooltip" title="Baggage Allowance Info"></i></small></li> <!-- Added "i" icon -->
                    </ul>

                    <!-- Include your Details Tabs Here -->
                    {{-- @include('partials.flight-details-tab', ['flightList' => $flightList, 'uniqueId' => $uniqueId, 'priceData' => $priceData])
                    @include('partials.fare-details-tab', ['results' => $results, 'uniqueId' => $uniqueId])
                    @include('partials.fare-rules-tab', ['uniqueId' => $uniqueId])
                    @include('partials.baggage-info-tab', ['price' => $segment['totalPriceList'][0] ?? [], 'uniqueId' => $uniqueId, 'flightList' => $flightList]) --}}
                </div>
            </div>

            <br>
            <span style="margin-left:20px; color: rgb(255, 138, 5); font-size:12px;">7 Seats Left</span>
        </div>
    @endforeach
@endif
    </div>
    <div class="col-md-6 m-0 p-0 ">
        
@if(isset($resultsRound['searchResult']['tripInfos']['RETURN']) && count($resultsRound['searchResult']['tripInfos']['RETURN']) > 0)
    @foreach($resultsRound['searchResult']['tripInfos']['RETURN'] as $index => $segment)
        @php
            $flightList = $segment['sI'];
            $firstFlight = $flightList[0];
            $lastFlight = end($flightList);

            $departureTime = \Carbon\Carbon::parse($firstFlight['dt']);
            $arrivalTime = \Carbon\Carbon::parse($lastFlight['at']);
            $durationMinutes = $arrivalTime->diffInMinutes($departureTime);
            $hours = intdiv($durationMinutes, 60);
            $minutes = $durationMinutes % 60;

            $stopovers = [];
            foreach ($flightList as $leg) {
                if (($leg['sN'] ?? 0) === 1) {
                    $stopovers[] = $leg['da'];
                }
            }

            $stopCount = count($stopovers);
            $stopLabel = $stopCount === 0 ? 'Non-stop' : "{$stopCount} stop" . ($stopCount > 1 ? 's' : '');

            $airlineCode = $firstFlight['fD']['aI']['code'] ?? 'XX';
            $airlineName = $firstFlight['fD']['aI']['name'] ?? 'Unknown Airline';
            $flightNumber = $firstFlight['fD']['fN'] ?? 'XXX';

            $fromCity = $firstFlight['da']['city'] ?? '';
            $toCity = $lastFlight['aa']['city'] ?? '';
            $fromTime = $departureTime->format('H:i');
            $toTime = $arrivalTime->format('H:i');
            $fromDate = $departureTime->format('d M');
            $toDate = $arrivalTime->format('d M');

            $logoPath = public_path("AirlinesLogo/{$airlineCode}.png");
            $logoUrl = file_exists($logoPath)
                ? asset("AirlinesLogo/{$airlineCode}.png")
                : asset("AirlinesLogo/default.png");

            $priceList = $segment['totalPriceList'] ?? [];
            $uniqueId = 'return_segment_' . $index;
        @endphp

        <div class="flight-card mb-4 p-3 border rounded shadow-sm bg-white">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    <img src="{{ $logoUrl }}" class="img-fluid" style="max-height: 40px;">
                    <p class="mt-2 mb-0 fw-bold text-dark">{{ $airlineName }}</p>
                    <small>{{ $flightNumber }}</small>
                </div>

                <div class="col-md-2 text-center">
                    <small class="text-muted">{{ $fromCity }}</small><br>
                    <h5 class="mb-1 fw-bold">{{ $fromTime }}</h5>
                    <small class="text-muted">{{ $fromDate }}</small>
                </div>

                <div class="col-md-2 text-center">
                    <span class="d-block fw-bold">{{ $hours }}h {{ $minutes }}m</span>
                    <div style="width: 100%; height: 2px; background-color: orange; position: relative; margin: 6px 0;">
                        <span style="position: absolute; right: -8px; top: -6px; color: orange; font-size: 18px;">→</span>
                    </div>
                    <small class="text-muted">{{ $stopLabel }}</small>
                </div>

                <div class="col-md-2 text-center">
                    <small class="text-muted">{{ $toCity }}</small><br>
                    <h5 class="mb-1 fw-bold">{{ $toTime }}</h5>
                    <small class="text-muted">{{ $toDate }}</small>
                </div>

                @php 
                    $containerId = 'return_fareBlock_' . $index;
                    $fareCount = count($priceList);
                @endphp
                <div class="col-md-2 text-start" id="{{ $containerId }}">
                    @foreach($priceList as $loopIndex => $priceItem)
                        @php
                            $totalFare = $priceItem['fd']['ADULT']['fC']['TF'] ?? 0;
                            $tag = $priceItem['fareIdentifier'] ?? 'Standard';
                            $cabinClassRaw = $priceItem['fd']['ADULT']['cc'] ?? 'N/A';
                            $cabinClass = \Illuminate\Support\Str::title(strtolower($cabinClassRaw));
                            $isHidden = $loopIndex >= 3 ? 'd-none' : '';
                        @endphp

                        <div class="mb-2 p-2 more-fare {{ $isHidden }}" data-fare-index="{{ $loopIndex }}">
                            <div class="d-flex">
                                <input class="form-check-input" type="radio">
                                <p class="mb-1">₹{{ number_format($totalFare, 2) }}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="badge bg-warning text-white text-uppercase">{{ $tag }}</span>
                                <small class="text-muted">{{ $cabinClass }}</small>
                            </div>
                        </div>
                    @endforeach

                    @if($fareCount > 3)
                        <button class="btn p-1" style="border:1px solid #ccc; font-size:12px; color:orange;" onclick="toggleMoreFares('{{ $containerId }}', this)">
                            +More Fares
                        </button>
                    @endif
                </div>

                {{-- <div class="col-md-2 text-center">
                    @php
                        $itineraryId = $priceItem['id'] ?? '';
                        $fareIdentifier = $priceItem['fareIdentifier'] ?? '';
                        $bookUrl = route('redirect.booking', [
                            'itineraryId' => $itineraryId,
                            'fareIdentifier' => $fareIdentifier
                        ]);
                    @endphp
                    <a href="{{ $bookUrl }}" class="btn btn-sm w-100 text-white" style="background-color: orange;">Book</a>
                </div> --}}
            </div>

            <div class="mt-3">
                <button type="button" id="view" class="btn btn-sm" style="background-color: #f5f5f5; color: rgb(255, 138, 5)" onclick="toggleDetails(this)">
                    View Details +
                </button>

                <div class="flight-details-content mt-2" style="display: none;">
                    {{-- @include('partials.flightDetails', ['flightList' => $flightList, 'uniqueId' => $uniqueId, 'priceData' => $priceList[0] ?? []])
                    @include('partials.fareDetails', ['uniqueId' => $uniqueId, 'results' => $results])
                    @include('partials.fareRules', ['uniqueId' => $uniqueId])
                    @include('partials.baggageInfo', ['uniqueId' => $uniqueId, 'price' => $segment['price'] ?? []]) --}}
                </div>
            </div>

            <br>
            <span style="margin-left:20px; color: rgb(255, 138, 5); font-size:12px;">7 Seats Left</span>
        </div>
    @endforeach
@endif
    </div>
</div>

{{-- multicity --}}

 @php
    $tripInfosmulticity = $resultsMulticity['searchResult']['tripInfos'] ?? [];
    $segments = [];

    foreach ($tripInfosmulticity as $i => $segmentGroup) {
        $firstSeg = $segmentGroup[0]['sI'][0] ?? null;
        $lastSeg = $segmentGroup[0]['sI'][count($segmentGroup[0]['sI']) - 1] ?? null;
        $from = $firstSeg['da']['city'] ?? 'Unknown';
        $to = $lastSeg['aa']['city'] ?? 'Unknown';
        $date = \Carbon\Carbon::parse($firstSeg['dt'])->format('d-m-Y');

        $segments[] = [
            'label' => "$from → $to",
            'date' => $date,
            'details' => $segmentGroup
        ];
    }
@endphp

{{-- Tabs --}}
<ul class="segment-tabs d-flex flex-nowrap">
    @foreach($segments as $index => $seg)
        <li class="{{ $index === 0 ? 'active' : '' }}" onclick="showSegment({{ $index }})">
            {{ $seg['label'] }}
            <small>{{ $seg['date'] }}</small>
        </li>
        
    @endforeach
</ul>
{{-- Segment flight blocks --}}
@foreach($segments as $index => $seg)
    <div class="segment-content" id="segment-{{ $index }}" style="{{ $index !== 0 ? 'display:none;' : '' }}">
        @foreach($seg['details'] as $comboIndex => $combo)
            @php
                $segments = $combo['sI'] ?? [];
                $priceData = $combo['totalPriceList'][0]['fd'] ?? null;
                $totalFare = $priceData['ADULT']['fC']['TF'] ?? 0;
                   
                $firstSeg = $segments[0];
                $lastSeg = end($segments);

                $fromCity = $firstSeg['da']['city'] ?? '';
                $toCity = $lastSeg['aa']['city'] ?? '';
                $fromTime = \Carbon\Carbon::parse($firstSeg['dt'])->format('H:i');
                $toTime = \Carbon\Carbon::parse($lastSeg['at'])->format('H:i');
                $fromDate = \Carbon\Carbon::parse($firstSeg['dt'])->format('d M');
                $toDate = \Carbon\Carbon::parse($lastSeg['at'])->format('d M');

                $durationMin = \Carbon\Carbon::parse($lastSeg['at'])->diffInMinutes(\Carbon\Carbon::parse($firstSeg['dt']));
                $hours = abs(intdiv($durationMin, 60));
                $minutes = abs($durationMin % 60);

                $airline = $firstSeg['fD']['aI'];
                $airlineCode = $airline['code'] ?? 'default';
                $logoPath = public_path("AirlinesLogo/{$airlineCode}.png");
                $logoUrl = file_exists($logoPath) ? asset("AirlinesLogo/{$airlineCode}.png") : asset("AirlinesLogo/default.png");

                $stopovers = [];
                if (count($segments) > 1) {
                    for ($i = 0; $i < count($segments) - 1; $i++) {
                        $stopovers[] = $segments[$i]['aa'];
                    }
                }

                $stopCount = count($stopovers);
                $stopLabel = $stopCount === 0 ? 'Non-stop' : "{$stopCount} stop" . ($stopCount > 1 ? 's' : '');

                $tooltipHTML = "<div class='card' style='width: 200px; padding: 10px; background-color: #fff; color: #000; border: 1px solid #ccc;'><div class='card-body'>";
                foreach ($stopovers as $stop) {
                    $city = $stop['city'] ?? '';
                    $code = $stop['code'] ?? '';
                    $name = $stop['name'] ?? '';
                    $tooltipHTML .= "<div class='mb-2'><strong>{$city} ({$code})</strong><br><small>{$name}</small></div><hr style='margin: 5px 0;'>";
                }
                $tooltipHTML .= "</div></div>";

                $class = strtoupper($priceData['ADULT']['cc'] ?? 'ECONOMY');
                $refundable = ($priceData['ADULT']['rT'] ?? 0) == 1 ? 'Refundable' : 'Non-refundable';
                $passengerTypes = ['ADULT' => 'Adult', 'CHILD' => 'Child', 'INFANT' => 'Infant'];
            @endphp

            {{-- Keep frontend from your flight-card or flight-multicity structure --}}
            <div class="flight-multicity border rounded shadow-sm mb-4 p-3 bg-white">
                <div class="row align-items-center border-bottom py-3">
                    <div class="col-md-2 text-center">
                        <div class="d-flex">
                            <img src="{{ $logoUrl }}" alt="{{ $airline['name'] }}" class="img-fluid" style="max-height: 20px;">
                            <p class="ms-2 fw-bold text-dark">{{ $airline['name'] }}</p>
                        </div>
                        <small class="text-muted mt-0">{{ $firstSeg['fD']['fN'] }}</small>
                    </div>

                    <div class="col-md-2 text-center">
                        <small class="text-muted">{{ $firstSeg['da']['code'] ?? '' }}</small><br>
                        <h5 class="mb-1 fw-bold">{{ $fromTime }}</h5>
                        <small class="text-muted">{{ $fromDate }}</small>
                    </div>

                    <div class="col-md-2 text-center">
                        @if($stopCount > 0)
                            <div class="position-relative d-inline-block">
                                <small class="text-muted" data-bs-toggle="tooltip" data-bs-html="true" title="{!! $tooltipHTML !!}">
                                    {{ $stopLabel }}
                                </small>
                            </div>
                        @else
                            <small class="text-muted">Non-stop</small>
                        @endif
                        <div style="width: 100%; height: 2px; background-color: orange; position: relative; margin: 6px 0;">
                            <span style="position: absolute; right: -8px; top: -6px; color: orange; font-size: 18px;">→</span>
                        </div>
                        <span class="d-block fw-bold">{{ $hours }}h {{ $minutes }}m</span>
                    </div>

                    <div class="col-md-2 text-center">
                        <small class="text-muted">{{ $lastSeg['aa']['code'] ?? '' }}</small><br>
                        <h5 class="mb-1 fw-bold">{{ $toTime }}</h5>
                        <small class="text-muted">{{ $toDate }}</small>
                    </div>

                    <div class="col-md-2 text-start">
                        @foreach ($passengerTypes as $key => $label)
                            @php $fare = $priceData[$key]['fC']['TF'] ?? 0; @endphp
                            @if ($fare > 0)
                                <div class="form-check mb-1 d-flex">
                                    <input class="form-check-input" type="radio" name="selected_fare" id="fare_{{ $comboIndex }}_{{ $key }}" value="{{ $comboIndex }}_{{ $key }}">
                                    <small class="d-block ms-3">₹{{ number_format($fare) }}.00</small>
                                </div>
                                <div class="d-flex mb-2">
                                    <small class="text-muted px-1" style="background-color: rgb(250, 202, 114); height:20px;">Published</small>
                                    <small class="text-muted d-flex ms-2">{{ Str::title(strtolower($class . ',' . $refundable)) }}</small>
                                </div>
                            @endif
                        @endforeach
                    </div>
{{-- @php
    $tripInfos =  $resultsMulticity['searchResult']['tripInfos'] ?? [];
@endphp

@foreach ($tripInfos as $segmentKey => $segment)
    <div class="segment-block mb-4">
        <h5>Segment {{ $segmentKey + 1 }}</h5>

        @foreach ($segment as $trip)
            @php
                $priceList = $trip['totalPriceList'] ?? [];
            @endphp

            @foreach ($priceList as $loopIndex => $priceItem)
                @php
                    $fareIdentifier = $priceItem['fareIdentifier'] ?? 'Standard';
                    $totalFare = $priceItem['fd']['ADULT']['fC']['TF'] ?? 0;
                    $cabinClassRaw = $priceItem['fd']['ADULT']['cc'] ?? 'N/A';
                    $cabinClass = \Illuminate\Support\Str::title(strtolower($cabinClassRaw));
                @endphp

                <div class="p-2 mb-2 border rounded">
                    <strong>₹{{ number_format($totalFare, 2) }}</strong>
                    <span class="badge bg-warning text-white">{{ $fareIdentifier }}</span>
                    <small class="text-muted">{{ $cabinClass }}</small>
                </div>
            @endforeach
        @endforeach
    </div>
@endforeach
 --}}


                 <div class="mt-3">
    <!-- Button -->
    <button type="button" id="view" class="btn btn-sm" style="background-color: #f5f5f5; color: rgb(255, 138, 5)" onclick="toggleDetails(this)">
        View Details +
    </button>

    <!-- Hidden Content: FLIGHT DETAILS -->
    <div class="flight-details-content mt-2" style="display: none;">

        <!-- TABS -->
        <ul class="segment-subtabs d-flex list-unstyled mb-0 border-bottom">
    <li class="tab-items active px-3 py-2"  style="cursor: pointer;"
    onclick="switchSubTab(this, 'flightDetailsContent_{{ $index }}_{{ $comboIndex }}')">
    <small>Flight Details</small>
</li>

    <li class="tab-items px-3 py-2" style="cursor: pointer;"
        onclick="switchSubTab(this, 'fareDetailsContent_{{ $index }}_{{ $comboIndex }}')">
        <small>Fare Details</small>
    </li>
    <li class="tab-items px-3 py-2"  style="cursor: pointer;"
        onclick="switchSubTab(this, 'fareRulesContent_{{ $index }}_{{ $comboIndex }}')">
        <small>Fare Rules</small>
    </li>
    <li class="tab-items px-3 py-2"  style="cursor: pointer;"
        onclick="switchSubTab(this, 'baggageInfoContent_{{ $index }}_{{ $comboIndex }}')">
        <small>Baggage Info</small>
    </li>
</ul>


        <!-- TAB CONTENT BOXES -->
    <div id="flightDetailsContent_{{ $index }}_{{ $comboIndex }}" style=" position: relative;" class="tab-subcontent bg-white cursor:pointer; p-3 shadow-sm rounded">
 <button onclick="closeDetails(this)" style="position: absolute; top: 10px; right: 10px; border: none; background: none; font-size: 16px; cursor: pointer;">✖</button>
    {{-- Header: Route + Date --}}
    @php
        $firstSeg = $segments[0];
        $lastSeg = end($segments);

        $fromCity = $firstSeg['da']['city'] ?? '';
        $fromCode = $firstSeg['da']['code'] ?? '';
        $toCity = $lastSeg['aa']['city'] ?? '';
        $toCode = $lastSeg['aa']['code'] ?? '';
        $departureDateTime = \Carbon\Carbon::parse($firstSeg['dt'])->format('D,d M Y');
    @endphp

    <div class="mb-4 border-bottom pb-3 d-flex">
        <h5 class="fw-bold mb-1 text-dark">
            {{ $fromCity }}  → {{ $toCity }}
        </h5>
        <small class="text-muted ms-2 mt-1">{{ $departureDateTime }}</small>
    </div>

    {{-- Segment-wise Flight Details --}}
    @foreach ($segments as $segmentIndex => $segData)
      @php
   $airlineCode = $segData['fD']['aI']['code'] ?? 'XX'; // e.g., "AI"
    $fullFlightCode = $segData['fD']['fN'] ?? 'XX-0000'; // e.g. AI-2433
    $flightNumber = $segData['fD']['fN'] ?? ''; // Should already be something like "6E-6016"
    $fullFlightCode = $flightNumber;

    $logoPath = public_path("AirlinesLogo/{$airlineCode}.png");
    $logoUrl = file_exists($logoPath) ? asset("AirlinesLogo/{$airlineCode}.png") : asset("AirlinesLogo/default.png");

    // Optional: extract airline code from full code (if needed elsewhere)
    $flightCarrierCode = explode('-', $fullFlightCode)[0];

    $depCountry = $segData['da']['country'] ?? ''; // Optional if available
    $depAirportName = $segData['da']['name'] ?? ''; // e.g. Delhi Indira Gandhi Intl
    $depAirport = $segData['da']['code'] ?? '';
    $depCity = $segData['da']['city'] ?? '';
    $depTime = \Carbon\Carbon::parse($segData['dt'])->format('d M,D,H:i');
 $classCode = $priceData['ADULT']['cc'] ?? 'ECONOMY'; // e.g., ECONOMY, BUSINESS
    $className = Str::title(strtolower($classCode));     // Properly formatted: "Economy"
   
    $arrTime = \Carbon\Carbon::parse($segData['at'])->format('M d, D, H:i'); // Jul 31, Thu, 10:00
    $arrCity = $segData['aa']['city'] ?? '';
    $arrCountry = $segData['aa']['country'] ?? ''; // Optional
    $arrAirportName = $segData['aa']['name'] ?? '';
    $durationMin = \Carbon\Carbon::parse($segData['at'])->diffInMinutes(\Carbon\Carbon::parse($segData['dt']));
    $durationHours = floor($durationMin / 60);
    $durationMinutes = $durationMin % 60;
    $durationText = "{$durationHours}h " . str_pad($durationMinutes, 2, '0', STR_PAD_LEFT) . "m";

    $isNonStop = true; // you can change this logic if you have multi-segment flight parts
    $stopText = $isNonStop ? 'Non-stop' : '1 stop'; // customize as needed
@endphp


        <div class="row mb-3 border-bottom pb-2 align-items-center">
            {{-- Airline Info --}}
           <div class="col-md-3 d-flex align-items-center justify-content-center">
   <img src="{{ $logoUrl }}" alt="{{ $airlineCode }}" style="max-height: 24px;" class="me-2 ">
    <div class="mt-2">
       
        <small class="text-muted text-dark">{{ $airlineCode }}-{{ $fullFlightCode }}</small>
        <p class="text-muted">{{ $className }}</p>
    </div>
</div>


            {{-- Departure Info --}}
            <div class="col-md-3 text-center">
              <small class="mb-1 fw-bold">{{ $depTime }}</small><br>
               <small class="mb-1">{{ $depCity }}{{ $depCountry ? ', ' . $depCountry : '' }}</small><br>
    <small class="mb-0 text-muted">{{ $depAirportName }}</small>
            </div>

            {{-- Arrow --}}
            <div class="col-md-2 text-center">
             
                 <small class="text-muted">{{ $stopText }}</small>
    <div style="width: 100%; height: 2px; background-color: orange; position: relative; margin: 6px 0;">
                            <span style="position: absolute; right: -8px; top: -6px; color: orange; font-size: 18px;">→</span>
                        </div>
    <div class="my-1 text-muted small">{{ $durationText }}</div>


            </div>

            {{-- Arrival Info --}}
            <div class="col-md-3 text-center">
    <small class="mb-1 fw-bold">{{ $arrTime }}</small><br>
    <small class="mb-1">{{ $arrCity }}{{ $arrCountry ? ', ' . $arrCountry : '' }}</small><br>
    <small class="mb-0 text-muted">{{ $arrAirportName }}</small>
</div>

        </div>
    @endforeach

</div>



       <div id="fareDetailsContent_{{ $index }}_{{ $comboIndex }}" class="tab-subcontent bg-white p-3 cursor:pointer; shadow-sm rounded d-none" style=" position: relative;">
  <button onclick="closeDetails(this)" style="position: absolute; top: 10px; right: 10px; border: none; background: none; font-size: 16px; cursor: pointer;">✖</button>
 @php
    $adultCount = request()->get('adult_count_multicity_unique', 1);
    $childCount = request()->get('child_count_multicity_unique', 0);
    $infantCount = request()->get('infant_count_multicity_unique', 0);

    $tripInfosmulticity = $resultsMulticity['searchResult']['tripInfos'] ?? [];
    $fare = $tripInfosmulticity[$index][$comboIndex]['totalPriceList'][0]['fd'] ?? [];

    // Adult fares
    $adultBase = $fare['ADULT']['fC']['BF'] ?? 0;
    $adultTax  = ($fare['ADULT']['fC']['TF'] ?? 0) - $adultBase;

    $totalAdultBase = $adultBase * $adultCount;
    $totalAdultTax  = $adultTax * $adultCount;

    // Tax breakdown for Adult
    $adultAfC = $fare['ADULT']['afC']['TAF'] ?? [];
    $managementFee = ($adultAfC['MF'] ?? 0);
    $mfTax         = ($adultAfC['MFT'] ?? 0);
    $yq            = ($adultAfC['YR'] ?? 0);
    $otherTaxes    = ($adultAfC['OT'] ?? 0);

    // Child fares
    $childBase = $fare['CHILD']['fC']['BF'] ?? 0;
    $childTax  = ($fare['CHILD']['fC']['TF'] ?? 0) - $childBase;

    $totalChildBase = $childBase * $childCount;
    $totalChildTax  = $childTax * $childCount;

    // Infant fares
    $infantBase = $fare['INFANT']['fC']['BF'] ?? 0;
    $infantTax  = ($fare['INFANT']['fC']['TF'] ?? 0) - $infantBase;

    $totalInfantBase = $infantBase * $infantCount;
    $totalInfantTax  = $infantTax * $infantCount;

    $grandTotal = $totalAdultBase + $totalAdultTax + $totalChildBase + $totalChildTax + $totalInfantBase + $totalInfantTax;
@endphp


    <table class="table border-0" style="border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 1px solid #eee;">
                <th class="border-0">TYPE</th>
                <th class="border-0">Fare</th>
                <th class="border-0">Total</th>
            </tr>
        </thead>
        <tbody>
            {{-- Adult --}}
            @if ($adultCount > 0)
                <tr><td colspan="3" style="color:#999; border: none;">Fare Details for Adult</td></tr>
                <tr>
                    <td class="border-0">Base Price</td>
                    <td class="border-0">₹{{ number_format($adultBase, 2) }} x {{ $adultCount }}</td>
                    <td class="border-0">₹{{ number_format($totalAdultBase, 2) }}</td>
                </tr>
                <tr>
                 <td class="border-0">
  Taxes and fees
 <i style="color:orange; font-weight:bold; "
     data-bs-toggle="tooltip"
     data-bs-placement="top"
     data-bs-html="true"
     title="Management Fee:          ₹{{ number_format($managementFee, 2) }}<br>
            Management Fee Tax:             ₹{{ number_format($mfTax, 2) }}<br>
            YQ:            ₹{{ number_format($yq, 2) }}<br>
            Other Taxes:        ₹{{ number_format($otherTaxes, 2) }}">
     i
  </i>
</td>

                    <td class="border-0">₹{{ number_format($adultTax, 2) }} x {{ $adultCount }}</td>
                    <td class="border-0">₹{{ number_format($totalAdultTax, 2) }}</td>
                </tr>
            @endif

            {{-- Child --}}
            @if ($childCount > 0)
                <tr><td colspan="3" style="color:#999; border: none;">Fare Details for Child</td></tr>
                <tr>
                    <td class="border-0">Base Price</td>
                    <td class="border-0">₹{{ number_format($childBase, 2) }} x {{ $childCount }}</td>
                    <td class="border-0">₹{{ number_format($totalChildBase, 2) }}</td>
                </tr>
                <tr>
                    <td class="border-0">
  Taxes and fees
 <i style="color:orange; font-weight:bold;"
     data-bs-toggle="tooltip"
     data-bs-placement="top"
     data-bs-html="true"
     title="Management Fee:          ₹{{ number_format($managementFee, 2) }}<br>
            Management Fee Tax:             ₹{{ number_format($mfTax, 2) }}<br>
            YQ:            ₹{{ number_format($yq, 2) }}<br>
            Other Taxes:        ₹{{ number_format($otherTaxes, 2) }}">
     i
  </i>
</td>

                    <td class="border-0">₹{{ number_format($childTax, 2) }} x {{ $childCount }}</td>
                    <td class="border-0">₹{{ number_format($totalChildTax, 2) }}</td>
                </tr>
            @endif

            {{-- Infant --}}
            @if ($infantCount > 0)
                <tr><td colspan="3" style="color:#999; border: none;">Fare Details for Infant</td></tr>
                <tr>
                    <td class="border-0">Base Price</td>
                    <td class="border-0">₹{{ number_format($infantBase, 2) }} x {{ $infantCount }}</td>
                    <td class="border-0">₹{{ number_format($totalInfantBase, 2) }}</td>
                </tr>
                <tr>
                  <td class="border-0">
  Taxes and fees
  <i style="color:orange; font-weight:bold; "
     data-bs-toggle="tooltip"
     data-bs-placement="top"
     data-bs-html="true"
     title="Management Fee:          ₹{{ number_format($managementFee, 2) }}<br>
            Management Fee Tax:             ₹{{ number_format($mfTax, 2) }}<br>
            YQ:            ₹{{ number_format($yq, 2) }}<br>
            Other Taxes:        ₹{{ number_format($otherTaxes, 2) }}">
     i
  </i>
</td>

                    <td class="border-0">₹{{ number_format($infantTax, 2) }} x {{ $infantCount }}</td>
                    <td class="border-0">₹{{ number_format($totalInfantTax, 2) }}</td>
                </tr>
            @endif

            {{-- Total --}}
            <tr style="border-top: 1px solid #eee;">
                <td colspan="2" class="border-0"><strong>Total</strong></td>
                <td class="border-0"><strong>₹{{ number_format($grandTotal, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>
</div>

         <div id="fareRulesContent_{{ $index }}_{{ $comboIndex }}" style=" position: relative;" class="tab-subcontent bg-white p-3 cursor:pointer; shadow-sm rounded d-none">
    <button onclick="closeDetails(this)" style="position: absolute; top: 10px; right: 10px; border: none; background: none; font-size: 16px; cursor: pointer;">✖</button> <p>Tickets are non-refundable. Change fee applicable.</p>
        </div>
       <div id="baggageInfoContent_{{ $index }}_{{ $comboIndex }}" style=" position: relative;" class="tab-subcontent bg-white p-3 cursor:pointer; shadow-sm rounded d-none">
               
    
    @php
        $tripInfosmulticity = $resultsMulticity['searchResult']['tripInfos'] ?? [];
        $flight = $tripInfosmulticity[$index][$comboIndex] ?? null;

        $from = $flight['sI'][0]['da']['code'] ?? 'N/A';
        $to = $flight['sI'][0]['aa']['code'] ?? 'N/A';
        $sector = "$from-$to";

        $fare = $flight['totalPriceList'][0]['fd'] ?? [];

        $adultCheckin = $fare['ADULT']['bI']['iB'] ?? '0 Kg';
        $childCheckin = $fare['CHILD']['bI']['iB'] ?? '0 Kg';

        $adultCabin = $fare['ADULT']['bI']['cB'] ?? '0 Kg';
        $childCabin = $fare['CHILD']['bI']['cB'] ?? '0 Kg';
        $infantCabin = $childCabin;
    @endphp

    <table class="table border-0" style="border-collapse: collapse;">
      <button onclick="closeDetails(this)" style="position: absolute; top: 10px; right: 10px; border: none; background: none; font-size: 16px; cursor: pointer;">✖</button>
        <thead>
            <tr style="border-bottom: 1px solid #eee;">
                <th class="border-0">Sector</th>
                <th class="border-0">Check-in Baggage</th>
                <th class="border-0">Cabin Baggage</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border-0">{{ $sector }}</td>
                <td class="border-0">
                    Adult: {{ $adultCheckin }} (01 Piece only)<br>
                    Child: {{ $childCheckin }} (01 Piece only)
                </td>
                <td class="border-0">
                    Adult: {{ $adultCabin }}<br>
                    Child: {{ $childCabin }}<br>
                    Infant: {{ $infantCabin }}
                </td>
            </tr>
        </tbody>
    </table>
</div>

    </div>

    <!-- SEAT INFO -->
    <br>
    <span style="margin-left:20px; color: rgb(255, 138, 5); font-size:12px;">7 Seats Left</span>
</div>

                </div>
            </div>
        @endforeach
    </div>
@endforeach


<!-- ✨ Move this outside any container -->
<div id="bottom-itinerary-bar" class="d-none"
     style="position: fixed; left: 0; bottom: 0; width: 100%; height:15%; background-color: #1a1a1a; color: white; z-index: 9999; padding: 10px 20px; box-shadow: 0 -2px 8px rgba(0,0,0,0.3); font-size: 13px; overflow-x: auto; white-space: nowrap;">
    <div id="route-details"
     style="display: flex; flex-direction: row; gap: 20px; overflow-x: auto; white-space: nowrap; flex-grow: 1;">
</div>
<br> 
    <button class="btn"
            style="position: absolute; background-color:orange;  right: 20px; top: 50%; transform: translateY(-50%);">BOOK</button>
</div>
</div>
</div>

              <div class="pagination-content text-center">
                <ul class="pagination">
                  <li>
                    <a href="#"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
                  </li>
                  <li class="active"><a href="#">1</a></li>
                  <li><a href="#">2</a></li>
                  <li><a href="#">3</a></li>
                  <li><a href="#">4</a></li>
                  <li>
                    <a href="#"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="list-sidebar">
              <div class="sidebar-item">
                <form class="filter-box">
                  <h3 class="white">Find The Places</h3>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="white">Your Destination</label>
                        <div class="input-box">
                          <i class="flaticon-placeholder"></i>
                          <select class="niceSelect">
                            <option value="1">Where are you going?</option>
                            <option value="2">Argentina</option>
                            <option value="3">Belgium</option>
                            <option value="4">Canada</option>
                            <option value="5">Denmark</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-6 col-md-6">
                      <div class="form-group">
                        <label class="white">Check In</label>
                        <div class="input-box">
                          <i class="flaticon-calendar"></i>
                          <input id="date-range0" type="text" placeholder="yyyy-mm-dd" />
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-6 col-md-6">
                      <div class="form-group">
                        <label class="white">Check Out</label>
                        <div class="input-box">
                          <i class="flaticon-calendar"></i>
                          <input id="date-range1" type="text" placeholder="yyyy-mm-dd" />
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-6 col-md-6">
                      <div class="form-group">
                        <label class="white">Adult</label>
                        <div class="input-box">
                          <i class="flaticon-add-user"></i>
                          <select class="niceSelect">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-6 col-md-6">
                      <div class="form-group">
                        <label class="white">Children</label>
                        <div class="input-box">
                          <i class="flaticon-add-user"></i>
                          <select class="niceSelect">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group mar-top-15">
                        <a href="#" class="biz-btn">Search</a>
                      </div>
                    </div>
                  </div>
                </form>
              </div>

              <div class="sidebar-item">
                <h3>Stops</h3>
                <div class="pretty p-default p-thick p-pulse">
                  <input type="checkbox" />
                  <div class="state">
                    <label>Non Stop</label>
                  </div>
                </div>
                <div class="pretty p-default p-thick p-pulse">
                  <input type="checkbox" checked />
                  <div class="state">
                    <label>1 Stop</label>
                  </div>
                </div>
                <div class="pretty p-default p-thick p-pulse">
                  <input type="checkbox" />
                  <div class="state">
                    <label>2 Stop</label>
                  </div>
                </div>
                <div class="pretty p-default p-thick p-pulse">
                  <input type="checkbox" />
                  <div class="state">
                    <label>3 Stop</label>
                  </div>
                </div>
              </div>
              <div class="sidebar-item">
                <h3>Price Range($)</h3>
                <div class="range-slider">
                  <div
                    data-min="0"
                    data-max="2000"
                    data-unit="$"
                    data-min-name="min_price"
                    data-max-name="max_price"
                    class="range-slider-ui ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"
                    aria-disabled="false"
                  >
                    <span class="min-value">0 $</span>
                    <span class="max-value">2000 $</span>
                    <div class="ui-slider-range ui-widget-header ui-corner-all full" style="left: 0%; width: 100%"></div>
                  </div>
                  <div class="clearfix"></div>
                </div>
              </div>
              <div class="sidebar-item">
                <h3>Airlines</h3>
                <div class="pretty p-default p-thick p-pulse">
                  <input type="checkbox" />
                  <div class="state">
                    <label>Air Asia<span class="number">749</span></label>
                  </div>
                </div>
                <div class="pretty p-default p-thick p-pulse">
                  <input type="checkbox" />
                  <div class="state">
                    <label>Thai Airlines<span class="number">749</span></label>
                  </div>
                </div>
                <div class="pretty p-default p-thick p-pulse">
                  <input type="checkbox" />
                  <div class="state">
                    <label>Turkish Airlines<span class="number">749</span></label>
                  </div>
                </div>
                <div class="pretty p-default p-thick p-pulse">
                  <input type="checkbox" checked />
                  <div class="state">
                    <label>United Airlines<span class="number">630</span></label>
                  </div>
                </div>
                <div class="pretty p-default p-thick p-pulse">
                  <input type="checkbox" />
                  <div class="state">
                    <label>Major Airlines<span class="number">58</span></label>
                  </div>
                </div>
                <div class="pretty p-default p-thick p-pulse">
                  <input type="checkbox" />
                  <div class="state">
                    <label>Dragon<span class="number">29</span></label>
                  </div>
                </div>
              </div>

              <div class="sidebar-item">
                <h3>Categories</h3>
                <div class="pretty p-default p-thick p-pulse">
                  <input type="checkbox" />
                  <div class="state">
                    <label>SEA TOURS (785)</label>
                  </div>
                </div>
                <div class="pretty p-default p-thick p-pulse">
                  <input type="checkbox" checked />
                  <div class="state">
                    <label>ROMANTIC TOURS (125)</label>
                  </div>
                </div>
                <div class="pretty p-default p-thick p-pulse">
                  <input type="checkbox" />
                  <div class="state">
                    <label>FOOD TOURS (85)</label>
                  </div>
                </div>
                <div class="pretty p-default p-thick p-pulse">
                  <input type="checkbox" />
                  <div class="state">
                    <label>HONEYMOON TOURS (70)</label>
                  </div>
                </div>
                <div class="pretty p-default p-thick p-pulse">
                  <input type="checkbox" />
                  <div class="state">
                    <label>MOUNTAIN TOURS (159)</label>
                  </div>
                </div>
              </div>
              <div class="sidebar-item">
                <h3>Facilities</h3>
                <div class="pretty p-default p-thick p-pulse">
                  <input type="checkbox" />
                  <div class="state">
                    <label>Snack With Drinks</label>
                  </div>
                </div>
                <div class="pretty p-default p-thick p-pulse">
                  <input type="checkbox" />
                  <div class="state">
                    <label>High Class Dinner</label>
                  </div>
                </div>
                <div class="pretty p-default p-thick p-pulse">
                  <input type="checkbox" />
                  <div class="state">
                    <label>Online Gaming</label>
                  </div>
                </div>
                <div class="pretty p-default p-thick p-pulse">
                  <input type="checkbox" />
                  <div class="state">
                    <label>Seat Television</label>
                  </div>
                </div>
              </div>
              <div class="sidebar-item">
                <h3>Flight Type</h3>
                <div class="pretty p-default p-thick p-pulse">
                  <input type="checkbox" />
                  <div class="state">
                    <label>Business<span class="number">749</span></label>
                  </div>
                </div>
                <div class="pretty p-default p-thick p-pulse">
                  <input type="checkbox" checked />
                  <div class="state">
                    <label>First Class<span class="number">630</span></label>
                  </div>
                </div>
                <div class="pretty p-default p-thick p-pulse">
                  <input type="checkbox" />
                  <div class="state">
                    <label>Economy<span class="number">58</span></label>
                  </div>
                </div>
                <div class="pretty p-default p-thick p-pulse">
                  <input type="checkbox" />
                  <div class="state">
                    <label>Premium Economy<span class="number">29</span></label>
                  </div>
                </div>
              </div>
              <div class="sidebar-item">
                <h3>Inflight Experience</h3>
                <div class="pretty p-default p-thick p-pulse">
                  <input type="checkbox" />
                  <div class="state">
                    <label>Inflight Dining</label>
                  </div>
                </div>
                <div class="pretty p-default p-thick p-pulse">
                  <input type="checkbox" checked />
                  <div class="state">
                    <label>Music</label>
                  </div>
                </div>
                <div class="pretty p-default p-thick p-pulse">
                  <input type="checkbox" />
                  <div class="state">
                    <label>Sky Shopping</label>
                  </div>
                </div>
                <div class="pretty p-default p-thick p-pulse">
                  <input type="checkbox" checked />
                  <div class="state">
                    <label>Seat & Cabin</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- flight-list ends -->
     <script src="{{ asset('js/flight-list.js') }}"></script>
<script>
function toggleDetails(button) {
    const content = button.nextElementSibling;
    const isVisible = content.style.display === 'block';

    content.style.display = isVisible ? 'none' : 'block';
    button.textContent = isVisible ? 'View Details +' : 'Hide Details -';
}

function closeDetails(closeBtn) {
    const content = closeBtn.closest('.flight-details-content');
    const toggleButton = content.previousElementSibling;

    content.style.display = 'none';
    toggleButton.textContent = 'View Details +';
}


function switchSubTab(tabEl, contentId) {
    // Remove active from all sibling tabs
    const tabContainer = tabEl.closest('.segment-subtabs');
    tabContainer.querySelectorAll('.tab-items').forEach(el => el.classList.remove('active'));
    tabEl.classList.add('active');

    // Hide all content inside the same .flight-details-content box
    const detailsBox = tabEl.closest('.flight-details-content');
    detailsBox.querySelectorAll('.tab-subcontent').forEach(el => el.classList.add('d-none'));

    // Show selected content
    const contentToShow = detailsBox.querySelector(`#${contentId}`);
    if (contentToShow) {
        contentToShow.classList.remove('d-none');
    }
}

</script>
<script>
    function toggleMoreFares(containerId, button) {
        const container = document.getElementById(containerId);
        const hiddenItems = Array.from(container.querySelectorAll('.more-fare.d-none'));
        const allItems = Array.from(container.querySelectorAll('.more-fare'));
        const batchSize = 3;

        if (hiddenItems.length > 0) {
            // Show next batch
            hiddenItems.slice(0, batchSize).forEach(item => item.classList.remove('d-none'));

            // If no more hidden items left, update button
            if (container.querySelectorAll('.more-fare.d-none').length === 0) {
                button.textContent = '- Less Fares';
            }
        } else {
            // Hide all except first 3
            allItems.forEach((item, index) => {
                item.classList.toggle('d-none', index >= 3);
            });
            button.textContent = '+ More Fares';
        }
    }
</script>

<script>


function toggleDetails(button) {
    const content = button.nextElementSibling;
    content.style.display = content.style.display === 'none' ? 'block' : 'none';
    button.textContent = content.style.display === 'none' ? 'View Details +' : 'Hide Details -';
}

function closeDetails(btn) {
    const contentBox = btn.closest('.flight-details-content');
    contentBox.style.display = 'none';
    const viewBtn = contentBox.previousElementSibling;
    if (viewBtn && viewBtn.tagName === 'BUTTON') {
        viewBtn.textContent = 'View Details +';
    }
}

function switchFlightTab(tabElement, tabContentId) {
    const parent = tabElement.closest('.flight-details-content');

    // Remove 'active' from all tabs
    const allTabs = parent.querySelectorAll('.tab-items');
    allTabs.forEach(tab => tab.classList.remove('active'));

    // Hide all tab contents
    const allContents = parent.querySelectorAll('.tab-subcontent');
    allContents.forEach(content => content.classList.add('d-none'));

    // Activate selected tab
    tabElement.classList.add('active');
    const activeContent = parent.querySelector(`#${tabContentId}`);
    if (activeContent) activeContent.classList.remove('d-none');
}
</script>

<script>
window.addEventListener("DOMContentLoaded", function () {
  const urlParams = new URLSearchParams(window.location.search);

  function getAllParamsByPrefix(prefix) {
    const results = [];
    for (const [key, value] of urlParams.entries()) {
      if (key.startsWith(prefix)) {
        results.push(value);
      }
    }
    return results;
  }

  const mcDiv = document.getElementById("multicity-summary-display");
  const owDiv = document.getElementById("oneway-summary-display");

  // Hide both by default
  if (mcDiv) mcDiv.style.display = "none";
  if (owDiv) owDiv.style.display = "none";

  // Detect multicity (by checking for at least one matching param)
  const isMulticity = getAllParamsByPrefix("from_where_multicity_unique").length > 0;
  const isOneway = urlParams.get("from_where_oneway[]") !== null;

  // Render Multicity
  if (isMulticity && mcDiv) {
    const fromCodes  = getAllParamsByPrefix("from_where_multicity_unique");
    const toCodes    = getAllParamsByPrefix("to_where_multicity_unique");
    const fromCities = getAllParamsByPrefix("from_where_text_multicity");
    const toCities   = getAllParamsByPrefix("to_where_text_multicity");
    const dates      = getAllParamsByPrefix("departure_date_multicity_unique");

    const travelClass = urlParams.get("travel_class_multicity_unique") || "ECONOMY";
    const adult = urlParams.get("adult_count_multicity_unique") || "1";
    const child = urlParams.get("child_count_multicity_unique") || "0";
    const infant = urlParams.get("infant_count_multicity_unique") || "0";
    const preferredAirline = urlParams.get("preferred_airline_multicity_unique") || "None";

    const passengerSummary = `${adult} Adult${child > 0 ? `, ${child} Child` : ""}${infant > 0 ? `, ${infant} Infant` : ""} | ${travelClass}`;

    let outputHtml = `<div class="multicity-summary-container px-4 d-flex flex-wrap align-items-center justify-content-center px-3 py-2 text-white" style="background-color:#2f2f2f;">`;

    for (let i = 0; i < fromCodes.length; i++) {
      outputHtml += `
        <div class="d-flex align-items-center me-4">
          <div class="text-center me-2">
            <div><strong>${fromCodes[i]}</strong></div>
            <div style="font-size: 12px;">${fromCities[i]}</div>
          </div>
          <div class="mx-2">✈</div>
          <div class="text-center me-2">
            <div><strong>${toCodes[i]}</strong></div>
            <div style="font-size: 12px;">${toCities[i]}</div>
          </div>
        </div>
        <div class="vr mx-2" style="height: 40px;"></div>`;
    }

    outputHtml += `
      <div class="me-4">
        <strong>Passengers & Class</strong><br>
        <small>${passengerSummary}</small>
      </div>
      <div class="vr mx-2" style="height: 40px;"></div>
      <div class="me-4">
        <strong>Preferred Airline</strong><br>
        <small>${preferredAirline}</small>
      </div>
      <div class="vr mx-2" style="height: 40px;"></div>
      <div class="ms-4">
        <button class="btn btn-outline-light btn-sm" onclick="toggleSearchTab('mc')">MODIFY SEARCH</button>
      </div>
    </div>`;

    mcDiv.innerHTML = outputHtml;
    mcDiv.style.display = "block";
  }

  // Render Oneway
  else if (isOneway && owDiv) {
    const fromText = urlParams.get("from_where_text") || "";
    const fromCode = urlParams.get("from_where_oneway[]") || "";
    const toText = urlParams.get("to_where_text") || "";
    const toCode = urlParams.get("to_where_oneway[]") || "";
    const depDate = urlParams.get("departure_date_oneway") || "";
    const travelClass = urlParams.get("travel_class_oneway") || "Economy";
    const adult = urlParams.get("adult_count_oneway") || "1";
    const child = urlParams.get("child_count_oneway") || "0";
    const infant = urlParams.get("infant_count_oneway") || "0";

    const passengerSummary = `${adult} Adult${child > 0 ? `, ${child} Child` : ""}${infant > 0 ? `, ${infant} Infant` : ""} | ${travelClass}`;

    const outputHtml = `
      <div class="multicity-summary-container px-4 d-flex flex-wrap align-items-center justify-content-center px-3 py-2 text-white" style="background-color:#2f2f2f;">
        <div class="d-flex align-items-center me-4">
          <div class="text-center me-2">
            <div><strong>${fromCode}</strong></div>
            <div style="font-size: 12px;">${fromText}</div>
          </div>
          <div class="mx-2">✈</div>
          <div class="text-center me-2">
            <div><strong>${toCode}</strong></div>
            <div style="font-size: 12px;">${toText}</div>
          </div>
        </div>
        <div class="vr mx-2" style="height: 40px;"></div>
        <div class="me-4">
          <strong>Departure Date</strong><br>
          <small>${depDate}</small>
        </div>
        <div class="vr mx-2" style="height: 40px;"></div>
        <div class="me-4">
          <strong>Passengers & Class</strong><br>
          <small>${passengerSummary}</small>
        </div>
        <div class="vr mx-2" style="height: 40px;"></div>
        <div class="me-4">
          <strong>Preferred Airline</strong><br>
          <small>None</small>
        </div>
        <div class="vr mx-2" style="height: 40px;"></div>
        <div class="ms-4">
          <button class="btn btn-outline-light btn-sm" onclick="toggleSearchTab('ow')">MODIFY SEARCH</button>
        </div>
      </div>`;

    owDiv.innerHTML = outputHtml;
    owDiv.style.display = "block";
  }
});
</script>

<script>

  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (tooltipTriggerEl) {
  new bootstrap.Tooltip(tooltipTriggerEl);
});
</script>
 <script>
document.addEventListener("DOMContentLoaded", function () {
    const flightData = @json($tripInfosmulticity);
    const radios = document.querySelectorAll('input[name="selected_fare"]');
    const itineraryBar = document.getElementById('bottom-itinerary-bar');
    const routeDetails = document.getElementById('route-details');

    radios.forEach((radio) => {
        radio.addEventListener('change', function () {
            const [comboIndexStr] = this.value.split('_');
            const comboIndex = parseInt(comboIndexStr);

            let allSegments = [];
            let allRoutes = [];
            let totalFare = 0;

            // Loop through all legs of the journey
            flightData.forEach((leg) => {
                const selectedCombo = leg[comboIndex];
                if (!selectedCombo || !selectedCombo.sI) return;

                const segments = selectedCombo.sI;
                totalFare += selectedCombo.totalPriceList?.[0]?.fd?.ADULT?.fC?.TF ?? 0;

                allSegments = allSegments.concat(segments);
                allRoutes.push(`${segments[0].da.city} → ${segments[segments.length - 1].aa.city}`);
            });

            const route = allRoutes.join(' | ');

            const segmentHtml = allSegments.map(seg => {
                const airlineName = seg.fD.aI.name;
                const flightNumber = seg.fD.fN;
                const airlineCode = seg.fD.aI.code;
                const logoUrl = `/AirlinesLogo/${airlineCode}.png`;
                const depTime = new Date(seg.dt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                const arrTime = new Date(seg.at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                return `
                    <div class="d-flex align-items-start gap-3 me-4 mb-2 border-end pe-3">
                        <div class="text-center">
                            <img src="${logoUrl}" onerror="this.src='/AirlinesLogo/default.png'" alt="${airlineName}" style="height: 28px;">
                            <div class="text-white small mt-1 fw-bold">${airlineName}</div>
                        </div>
                        <div class="d-flex flex-column">
                            <div class="text-white small mb-1"><strong>${airlineCode}-${flightNumber}</strong></div>
                            <div class="text-white small mb-1">${depTime} → ${arrTime}</div>
                            <div class="text-white small mb-1">${seg.da.code} → ${seg.aa.code}</div>
                        </div>
                    </div>
                `;
            }).join('');

            routeDetails.innerHTML = `
                <div>
                    <div class="text-white fw-bold mb-2">Route: ${route}</div>
                    ${segmentHtml}
                    <div class="text-white fw-bold mt-2">Fare: ₹${totalFare.toLocaleString('en-IN')}</div>
                </div>
            `;

            itineraryBar.classList.remove('d-none');
        });
    });
});

</script>

<script>
    .then(data => {
  console.log("Flight Search Results:", data);

  const resultsContainer = document.getElementById("flightResults");
  resultsContainer.innerHTML = ""; // Clear old results

  const flights = data.searchResult?.tripInfos?.ONWARD;

  if (!flights || flights.length === 0) {
    resultsContainer.innerHTML = "<p>No flights found.</p>";
    return;
  }

  flights.forEach(flight => {
    const segment = flight.sI[0]; // First flight segment

    const airline = segment.fD.aI.name;
    const flightCode = segment.fD.aI.code + segment.fD.fN;
    const depTime = segment.dt.slice(11, 16); // "HH:MM"
    const arrTime = flight.sI[flight.sI.length - 1].at.slice(11, 16); // "HH:MM"
    const fromCity = segment.da.city;
    const toCity = flight.sI[flight.sI.length - 1].aa.city;
    const duration = segment.duration; // in minutes
    const totalFare = flight.totalPriceList[0]?.fd?.ADULT?.fC?.TF || "N/A";

    // Convert duration to H M
    const hours = Math.floor(duration / 60);
    const minutes = duration % 60;
    const durationText = `${hours}H ${minutes}M`;

    const card = `
      <div class="item mar-bottom-30">
        <div class="row">
          <div class="col-lg-3 col-md-3">
            <div class="item-inner">
              <img src="{{ asset('images/flight_grid_3.png') }}" alt="Flight Image" />
              <p style="font-weight: bold; margin-top: 10px;">${airline} (${flightCode})</p>
            </div>
          </div>
          <div class="col-lg-2 col-md-2">
            <div class="item-inner text-center">
              <div class="content">
                <h3>${depTime}</h3>
                <p>${fromCity}</p>
              </div>
            </div>
          </div>
          <div class="col-lg-2 col-md-2">
            <div class="item-inner text-center flight-time">
              <p class="bold">${durationText}</p>
            </div>
          </div>
          <div class="col-lg-2 col-md-2">
            <div class="item-inner text-center">
              <div class="content">
                <h3>${arrTime}</h3>
                <p>${toCity}</p>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-3">
            <div class="item-inner flight-btn text-center">
              <p>₹${totalFare}</p>
              <a href="#" class="biz-btn biz-btn1">Book Now</a>
            </div>
          </div>
        </div>
      </div>
    `;

    resultsContainer.innerHTML += card;
  });
})
</script>
{{-- <script>
    document.addEventListener("DOMContentLoaded", function () {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

<!-- Tooltip activation (Bootstrap 5) -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
      <script>
 function setToAndFromValues(toInputElement) {
    const toValue = toInputElement.value;
    const hiddenIdAttr = toInputElement.dataset.hiddenId;
    const toHiddenInput = document.getElementById(hiddenIdAttr);
    const toHiddenValue = toHiddenInput?.value || "";

    // ✅ Target the exact second "From" field by ID
    const fromTextInput = document.querySelector('input[data-hidden-id="from_where_multicity_unique1"]');
    const fromHiddenInput = document.getElementById('from_where_multicity_unique1');

    if (fromTextInput && fromHiddenInput) {
        fromTextInput.value = toValue;
        fromHiddenInput.value = toHiddenValue;
    }
}

</script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    // Counter buttons specific to multicity
    document.querySelectorAll(".traveler-btn[data-context='multicity_unique']").forEach(function (btn) {
      btn.addEventListener("click", function () {
        const type = this.getAttribute("data-type");
        const action = this.getAttribute("data-action");

        const countEl = document.getElementById(`${type}CountMulticityUnique`);
        const inputEl = document.getElementById(`${type}CountInputMulticityUnique`);

        let count = parseInt(countEl.innerText) || 0;

        if (action === "minus") {
          if (type === "adult" && count > 1) count--;
          else if ((type === "child" || type === "infant") && count > 0) count--;
        } else if (action === "plus") {
          count++;
        }

        countEl.innerText = count;
        inputEl.value = count;

        updateTravelerSummaryMulticity();
      });
    });

    // Class dropdown change listener
    const classSelect = document.getElementById("travelClassMulticityUnique");
    if (classSelect) {
      classSelect.addEventListener("change", updateTravelerSummaryMulticity);
    }

    // Init summary once page loads
    updateTravelerSummaryMulticity();
  });

  function updateTravelerSummaryMulticity() {
    const adults = parseInt(document.getElementById("adultCountMulticityUnique").innerText) || 0;
    const children = parseInt(document.getElementById("childCountMulticityUnique").innerText) || 0;
    const infants = parseInt(document.getElementById("infantCountMulticityUnique").innerText) || 0;
    const travelClass = document.getElementById("travelClassMulticityUnique").value;

    const total = adults + children + infants;
    const summary = `${total} Traveler${total > 1 ? 's' : ''} - ${travelClass}`;

    document.getElementById("travelerSummaryMulticityUnique").innerText = summary;

    const hiddenInput = document.getElementById("travelSummaryInputMulticityUnique");
    if (hiddenInput) hiddenInput.value = summary;
  }
</script>

<script>
document.getElementById("searchFormOneway").addEventListener("submit", function (e) {
    e.preventDefault();

    const fromWhereCode = document.getElementById("from_where_oneway").value;
    const toWhereCode = document.getElementById("to_where_oneway").value;
    const departureDate = document.getElementById("date-range0-oneway").value;

    const adult = document.getElementById("adultCountInputOneway").value;
    const child = document.getElementById("childCountInputOneway").value;
    const infant = document.getElementById("infantCountInputOneway").value;
    const travelClass = document.getElementById("travelClassOneway").value;

    const queryString = new URLSearchParams({
        travel_type: "One Way",
        "from_where[]": fromWhereCode,
        "to_where[]": toWhereCode,
        "start[]": departureDate,
        "date": departureDate,
        "seat_type[adults]": adult,
        "seat_type[children]": child,
        "seat_type[infants]": infant,
        "seat_type[class]": travelClass
    }).toString();

    this.action = `/tripjack/search?${queryString}`; // ✅ Use your route here

    this.submit();
});
</script>
<script>
document.getElementById("searchFormRoundtrip").addEventListener("submit", function (e) {
    e.preventDefault();

    const fromWhereCode = document.getElementById("from_where").value;
    const toWhereCode = document.getElementById("to_where").value;
    const departureDate = document.getElementById("date-range0").value;
    const ReturnDate = document.getElementById("date-range1").value;
    const adult = document.getElementById("adultCountInput").value;
    const child = document.getElementById("childCountInput").value;
    const infant = document.getElementById("infantCountInput").value;
    const travelClass = document.getElementById("travelClass").value;

    const queryString = new URLSearchParams({
        travel_type: "Round Trip",
        "from_where[]": fromWhereCode,
        "to_where[]": toWhereCode,
        "start[]": departureDate,
        "date": departureDate,
         "start[]": ReturnDate,
        "date": ReturnDate,
        "seat_type[adults]": adult,
        "seat_type[children]": child,
        "seat_type[infants]": infant,
        "seat_type[class]": travelClass
    }).toString();

    this.action = `/tripjack/search?${queryString}`; // ✅ Use your route here

    this.submit();
});
</script>
<script>
  document.getElementById("searchFormRoundtrip").addEventListener("submit", function (e) {
    e.preventDefault();

    const fromWhereCode = document.getElementById("from_where").value;
    const toWhereCode = document.getElementById("to_where").value;
    const departureDate = document.getElementById("date-range0").value;
    const returnDate = document.getElementById("date-range1").value;
    const adult = document.getElementById("adultsInput").value;
    const child = document.getElementById("childrenInput").value;
    const infant = document.getElementById("infantsInput").value;
    const travelClass = document.getElementById("travelClass").value;

    const queryString = new URLSearchParams({
        travel_type: "Round Trip",
        from_where: fromWhereCode,
        to_where: toWhereCode,
        depart_date: departureDate,
        return_date: returnDate,
        "seat_type[adults]": adult,
        "seat_type[children]": child,
        "seat_type[infants]": infant,
        "seat_type[class]": travelClass
    }).toString();

    this.action = `/tripjack/search/roundtrip?${queryString}`;
    this.submit();
});
</script>
<script>
document.getElementById("searchFormMulticityUnique").addEventListener("submit", function (e) {
  e.preventDefault();

  const form = this;
  const segments = form.querySelectorAll('.segment-wrapper-multicity');
  const queryParams = new URLSearchParams();

  queryParams.set("travel_type", "Multicity");

  let valid = true;

  segments.forEach((segment, index) => {
    const fromText = segment.querySelector('input[name^="from_where_text_multicity"]');
    const fromCode = segment.querySelector('input[name^="from_where_multicity_unique"]');
    const toText = segment.querySelector('input[name^="to_where_text_multicity"]');
    const toCode = segment.querySelector('input[name^="to_where_multicity_unique"]');
    const dateInput = segment.querySelector('input[name^="departure_date_multicity_unique"]');

    // Optional: Validate airport codes
    // if (!fromCode.value || !toCode.value) {
    //   alert("Please select valid airports from the dropdown.");
    //   valid = false;
    //   return;
    // }

    queryParams.append("from_where_text_multicity[]", fromText?.value.trim() || '');
    queryParams.append("from_where_multicity_unique[]", fromCode?.value.trim() || '');
    queryParams.append("to_where_text_multicity[]", toText?.value.trim() || '');
    queryParams.append("to_where_multicity_unique[]", toCode?.value.trim() || '');
    queryParams.append("departure_date_multicity_unique[]", dateInput?.value.trim() || '');
  });

  if (!valid) return;

  // Traveler details
  const adults = document.getElementById('adultCountInputMulticityUnique')?.value || '1';
  const children = document.getElementById('childCountInputMulticityUnique')?.value || '0';
  const infants = document.getElementById('infantCountInputMulticityUnique')?.value || '0';
  const travelClass = document.getElementById('travelClassMulticityUnique')?.value || 'Economy';

  queryParams.set("adult_count_multicity_unique", adults);
  queryParams.set("child_count_multicity_unique", children);
  queryParams.set("infant_count_multicity_unique", infants);
  queryParams.set("travel_class_multicity_unique", travelClass);

  const travelerSummary = `${adults} Adult${children > 0 ? `, ${children} Child` : ''} - ${travelClass}`;
  queryParams.set("travel_summary_multicity_unique", travelerSummary);

  // Final submit
  form.action = `/tripjack/search/multicity?${queryParams.toString()}`;
  form.submit();
});

</script>
<script>
window.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);

    function getAllWithPrefix(prefix) {
        const results = [];
        for (const [key, value] of urlParams.entries()) {
            if (key.startsWith(prefix)) {
                results.push(value);
            }
        }
        return results;
    }

    const fromCities = getAllWithPrefix("from_where_text_multicity");
    const fromCodes = getAllWithPrefix("from_where_multicity_unique");
    const toCities = getAllWithPrefix("to_where_text_multicity");
    const toCodes = getAllWithPrefix("to_where_multicity_unique");
    const dates = getAllWithPrefix("departure_date_multicity_unique");

    const travelClass = urlParams.get("travel_class_multicity_unique") || "ECONOMY";
    const adult = urlParams.get("adult_count_multicity_unique") || "1";
    const child = urlParams.get("child_count_multicity_unique") || "0";
    const infant = urlParams.get("infant_count_multicity_unique") || "0";
    const preferredAirline = urlParams.get("preferred_airline_multicity_unique") || "None";

    const passengerSummary = `${adult} Adult${child > 0 ? `, ${child} Child` : ""}${infant > 0 ? `, ${infant} Infant` : ""} | ${travelClass}`;

    let outputHtml = `
        <div class="multicity-summary-container px-4 d-flex flex-wrap align-items-center justify-content-center px-3 py-2 text-white" style="background-color:#2f2f2f;">
    `;

    // Routes
    for (let i = 0; i < fromCities.length; i++) {
        outputHtml += `
            <div class="d-flex align-items-center me-4">
                <div class="text-center me-2">
                    <div><strong>${fromCodes[i]}</strong></div>
                    <div style="font-size: 12px;">${fromCities[i]}</div>
                </div>
                <div class="mx-2">✈</div>
                <div class="text-center me-2">
                    <div><strong>${toCodes[i]}</strong></div>
                    <div style="font-size: 12px;">${toCities[i]}</div>
                </div>
            </div>
        `;

        // Add separator after each route, unless it's the last route and other sections follow
        if (i < fromCities.length - 1 || true) {
            outputHtml += `<div class="vr mx-2" style="height: 40px;"></div>`;
        }
    }

    // Passengers & Class
    outputHtml += `
        <div class="me-4">
            <strong>Passengers & Class</strong><br>
            <small>${passengerSummary}</small>
        </div>
        <div class="vr mx-2" style="height: 40px;"></div>
    `;

    // Preferred Airline
    outputHtml += `
        <div class="me-4">
            <strong>Preferred Airline</strong><br>
            <small>${preferredAirline}</small>
        </div>
        <div class="vr mx-2" style="height: 40px;"></div>
    `;

    // Modify button
    outputHtml += `
        <div class="ms-4">
           <button class="btn btn-outline-light btn-sm" onclick="toggleSearchTab('mc')">MODIFY SEARCH</button>
        </div>
    </div>`;

    const container = document.getElementById("multicity-summary-display");
    if (container) {
        container.innerHTML = outputHtml;
    }
});
</script>


<script>
function toggleSearchTab(tabKey) {
    const tabIds = {
        rt: "roundtrip",
        ow: "oneway",
        mc: "multicity"
    };

    // Show banner-form section if hidden
    const bannerForm = document.querySelector(".banner-form");
    if (bannerForm && bannerForm.classList.contains("d-none")) {
        bannerForm.classList.remove("d-none");
    }

    // Hide all tab buttons & panes
    document.querySelectorAll(".nav-link").forEach(link => link.classList.remove("active"));
    document.querySelectorAll(".tab-pane").forEach(pane => pane.classList.remove("active", "show"));

    // Show the correct tab and pane
    const navLink = document.getElementById(tabKey); // e.g. 'rt', 'ow', 'mc'
    const tabPaneId = tabIds[tabKey];

    if (navLink) navLink.classList.add("active");
    if (tabPaneId) {
        const pane = document.getElementById(tabPaneId);
        if (pane) {
            pane.classList.add("active", "show");
            pane.scrollIntoView({ behavior: "smooth" });
        }
    }

    // Hide multicity summary if visible
    const summary = document.getElementById("multicity-summary-display");
    if (summary) {
        summary.style.display = "none";
    }
}

</script>
<script>
window.addEventListener("DOMContentLoaded", () => {
  // Helper: Parse query params into an object with arrays support
  function getQueryParams() {
    const params = new URLSearchParams(window.location.search);
    const data = {};

    for (const [key, value] of params.entries()) {
      // Support array keys like key[] or key[1]
      const arrayKeyMatch = key.match(/^([^\[]+)(\[(.*?)\])?$/);
      if (!arrayKeyMatch) continue;

      const baseKey = arrayKeyMatch[1];
      const index = arrayKeyMatch[3]; // might be empty or number or empty string for []

      if (!data[baseKey]) data[baseKey] = [];

      if (index === undefined || index === "") {
        // Push to array for keys like from_where_multicity_unique[]
        data[baseKey].push(value);
      } else {
        // Set at index for keys like from_where_multicity_unique[1]
        data[baseKey][parseInt(index)] = value;
      }
    }

    return data;
  }

  // Get the parsed params
  const params = getQueryParams();

  // Fill inputs with matching name patterns
  // Example: from_where_multicity_unique[]
  if (params["from_where_multicity_unique"]) {
    params["from_where_multicity_unique"].forEach((val, i) => {
      // Hidden inputs (no index or with index)
      const hiddenInput = document.querySelector(
        `input[name="from_where_multicity_unique${i !== 0 ? `[${i}]` : "[]" }"]`
      );
      if (hiddenInput) hiddenInput.value = val;
    });
  }

  if (params["from_where_text_multicity"]) {
    params["from_where_text_multicity"].forEach((val, i) => {
      const textInput = document.querySelector(
        `input[name="from_where_text_multicity${i !== 0 ? `[${i}]` : "[]" }"]`
      );
      if (textInput) textInput.value = val;
    });
  }

  if (params["to_where_multicity_unique"]) {
    params["to_where_multicity_unique"].forEach((val, i) => {
      const hiddenInput = document.querySelector(
        `input[name="to_where_multicity_unique${i !== 0 ? `[${i}]` : "[]" }"]`
      );
      if (hiddenInput) hiddenInput.value = val;
    });
  }

  if (params["to_where_text_multicity"]) {
    params["to_where_text_multicity"].forEach((val, i) => {
      const textInput = document.querySelector(
        `input[name="to_where_text_multicity${i !== 0 ? `[${i}]` : "[]" }"]`
      );
      if (textInput) textInput.value = val;
    });
  }

  if (params["departure_date_multicity_unique"]) {
    params["departure_date_multicity_unique"].forEach((val, i) => {
      // Departure dates have only [] no indices?
      // Try to match the i-th departure date input (all with same name)
      const inputs = document.querySelectorAll('input[name="departure_date_multicity_unique[]"]');
      if (inputs && inputs[i]) inputs[i].value = val;
    });
  }

  // Counts and selects (single values)
  if (params["adult_count_multicity_unique"]) {
    const adultInput = document.getElementById("adultCountInputMulticityUnique");
    const adultSpan = document.getElementById("adultCountMulticityUnique");
    if (adultInput) adultInput.value = params["adult_count_multicity_unique"][0];
    if (adultSpan) adultSpan.textContent = params["adult_count_multicity_unique"][0];
  }
  if (params["child_count_multicity_unique"]) {
    const childInput = document.getElementById("childCountInputMulticityUnique");
    const childSpan = document.getElementById("childCountMulticityUnique");
    if (childInput) childInput.value = params["child_count_multicity_unique"][0];
    if (childSpan) childSpan.textContent = params["child_count_multicity_unique"][0];
  }
  if (params["infant_count_multicity_unique"]) {
    const infantInput = document.getElementById("infantCountInputMulticityUnique");
    const infantSpan = document.getElementById("infantCountMulticityUnique");
    if (infantInput) infantInput.value = params["infant_count_multicity_unique"][0];
    if (infantSpan) infantSpan.textContent = params["infant_count_multicity_unique"][0];
  }

  if (params["travel_class_multicity_unique"]) {
    const classSelect = document.getElementById("travelClassMulticityUnique");
    if (classSelect) classSelect.value = params["travel_class_multicity_unique"][0];
  }

  // Update the travel summary text (if needed)
  if (params["travel_summary_multicity_unique"]) {
    const summaryInput = document.getElementById("travelSummaryInputMulticityUnique");
    if (summaryInput) summaryInput.value = params["travel_summary_multicity_unique"][0];
  }
});


</script>
<script>
window.addEventListener('DOMContentLoaded', () => {
  const buttons = document.querySelectorAll('.closeBtn'); // use . for class

  buttons.forEach(button => {
    button.addEventListener('click', () => {
      const multicityPane = document.getElementById('flight-add');
      const summary = document.getElementById('multicity-summary-display');

      if (multicityPane) {
        multicityPane.style.display = 'none';  // hide multicity pane
      }
      if (summary) {
        summary.style.display = 'block'; // show summary
      }
    });
  });
});


</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('#multiCityTabs .nav-link');
    const contents = document.querySelectorAll('.flight-tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function (e) {
            e.preventDefault();

            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            const targetId = this.getAttribute('data-tab');
            contents.forEach(c => c.classList.add('d-none'));
            document.getElementById(targetId).classList.remove('d-none');
        });
    });
});
</script>

{{-- JS for tab switching --}}
@endsection