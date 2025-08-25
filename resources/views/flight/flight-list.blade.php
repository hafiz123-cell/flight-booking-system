@extends('layout.layout')

<style>
  #roundtrip-summary-display {
    position: sticky;
    top: 0;
    z-index: 10;
  }

  .list.flight-list {
    z-index: 9;
    padding-bottom: 0px !important;
  }

  #filterOffcanvas {
    position: fixed;
    top: 0;
    right: -100%;
    width: 100%;
    height: 100vh;
    background-color: #3f3f3fa6;
    transition: right 0.3s ease-in-out;
    z-index: 1050;
    overflow-y: hidden;
    display: flex;
    justify-content: end;

    &.active {
      right: 0;
    }
  }

  #filterOffcanvas .InnerDiv {
    background-color: #fff;
    box-shadow: -2px 0 5px rgba(0, 0, 0, 0.5);
    max-width: 420px;
    width: 100%;
    height: 100%;
    position: relative;

    & .closeButton {
      display: flex;
      justify-content: start;
      padding: 10px;
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      background: #fff;
      z-index: 9;

      & button {
        background: transparent;
        border: none;
        font-size: 24px;
        cursor: pointer;
      }
    }

    & .list-sidebar {
      padding: 60px 16px 16px;
      height: 100%;
      overflow: auto;

      &::-webkit-scrollbar {
        width: 5px;
      }

      /* Track */
      &::-webkit-scrollbar-track {
        background: #f1f1f1;
      }

      /* Handle */
      &::-webkit-scrollbar-thumb {
        background: #888;
      }

      /* Handle on hover */
      &::-webkit-scrollbar-thumb:hover {
        background: #555;
      }
    }
  }
</style>


@section('content')
<div id="multicity-summary-display" style="display: none;">
  <div style="display:flex; justify-content:center;"></div>
</div>

<div id="oneway-summary-display" style="display: none;">
  <div style="display:flex; justify-content:center;"></div>
</div>

<div id="roundtrip-summary-display" style="display: none;">
  <div style="display:flex; justify-content:center;"></div>
</div>

<div id="filterOffcanvas">
  <div class="InnerDiv">
    <div class="closeButton">
      <button onclick="closeFilterOffcanvas()">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M18 6l-12 12" />
          <path d="M6 6l12 12" />
        </svg>
      </button>
    </div>
    <div class="list-sidebar">

      <!-- stops -->
      <div class="sidebar-item">
        <h3>Stops</h3>
        @php
          $stops = [];
            if(isset($resultsRound['searchResult']['tripInfos']['ONWARD'])) {
              foreach($resultsRound['searchResult']['tripInfos']['ONWARD'] as $segment) {
                  $flightList = $segment['sI'];
                  $stopovers = [];
                  foreach ($flightList as $leg) {
                      if (($leg['sN'] ?? 0) === 1) {
                          $stopovers[] = $leg['da'];
                      }
                  }
                  $stopCount = count($stopovers);

                  if (!isset($stops[$stopCount])) {
                      $stops[$stopCount] = 1;
                  } else {
                      $stops[$stopCount]++;
                  }
              }
            }elseif(isset($results['searchResult']['tripInfos']['ONWARD'])){
              foreach($results['searchResult']['tripInfos']['ONWARD'] as $segment) {
                  $flightList = $segment['sI'];
                  $stopovers = [];
                  foreach ($flightList as $leg) {
                      if (($leg['sN'] ?? 0) === 1) {
                          $stopovers[] = $leg['da'];
                      }
                  }
                  $stopCount = count($stopovers);

                  if (!isset($stops[$stopCount])) {
                      $stops[$stopCount] = 1;
                  } else {
                      $stops[$stopCount]++;
                  }
              }
            } elseif(isset($resultsMulticity['searchResult']['tripInfos'])) {
               foreach ($resultsMulticity['searchResult']['tripInfos'] as $segmentGroup) {
                foreach ($segmentGroup as $segment) {
                    $flightList = $segment['sI'];
                    $stopovers = [];
                    foreach ($flightList as $leg) {
                        if (($leg['sN'] ?? 0) === 1) {
                            $stopovers[] = $leg['da'];
                        }
                    }
                    $stopCount = count($stopovers);
                    $stops[$stopCount] = ($stops[$stopCount] ?? 0) + 1;
                }
                }
            }
        @endphp

        @foreach($stops as $count => $total)
          <div class="pretty p-default p-thick p-pulse">
            <input type="checkbox" value="{{ $count }}" class="filter-stop"/>
            <div class="state">
              <label>
                {{ $count == 0 ? 'Non Stop' : $count . ' Stop' . ($count > 1 ? 's' : '') }}
                <span class="number">{{ $total }}</span>
              </label>
            </div>
          </div>
        @endforeach

      </div>

      @php
          $allPrices = [];
          $globalMinPrice = 0;
          $globalMaxPrice = 2000;
        if(isset($resultsRound['searchResult']['tripInfos']['ONWARD'])) {
          foreach($resultsRound['searchResult']['tripInfos']['ONWARD'] as $segment){
              foreach(($segment['totalPriceList'] ?? []) as $priceItem){
                  $allPrices[] = $priceItem['fd']['ADULT']['fC']['TF'] ?? 0;
              }
          }
          $globalMinPrice = !empty($allPrices) ? min($allPrices) : 0;
          $globalMaxPrice = !empty($allPrices) ? max($allPrices) : 0;
        }elseif(isset($results['searchResult']['tripInfos']['ONWARD'])){
           foreach($results['searchResult']['tripInfos']['ONWARD'] as $segment){
              foreach(($segment['totalPriceList'] ?? []) as $priceItem){
                  $allPrices[] = $priceItem['fd']['ADULT']['fC']['TF'] ?? 0;
              }
          }
          $globalMinPrice = !empty($allPrices) ? min($allPrices) : 0;
          $globalMaxPrice = !empty($allPrices) ? max($allPrices) : 0;
        } elseif(isset($resultsMulticity['searchResult']['tripInfos'])) {
            foreach($resultsMulticity['searchResult']['tripInfos'] as $segmentGroup) {
                foreach($segmentGroup as $segment) {
                    foreach(($segment['totalPriceList'] ?? []) as $priceItem){
                        $allPrices[] = $priceItem['fd']['ADULT']['fC']['TF'] ?? 0;
                    }
                }
            }
            $globalMinPrice = !empty($allPrices) ? min($allPrices) : 0;
            $globalMaxPrice = !empty($allPrices) ? max($allPrices) : 0;
        }
      @endphp

      <!-- price range -->
      <div class="sidebar-item">
        <h3>Price Range (₹)</h3>
        <div class="range-slider">
          <div id="priceRange"></div>
          <div class="d-flex justify-content-between mt-2">
            <span class="min-value">{{ number_format($globalMinPrice) }} ₹</span>
            <span class="max-value">{{ number_format($globalMaxPrice) }} ₹</span>
          </div>
        </div>
      </div>


      <!-- airlines -->
      <div class="sidebar-item">
        <h3>Airlines</h3>
         @php
            $airlines = [];
                  if(isset($resultsRound['searchResult']['tripInfos']['ONWARD'])) {
                      foreach($resultsRound['searchResult']['tripInfos']['ONWARD'] as $segment) {
                          $flightList = $segment['sI'];
                          $firstFlight = $flightList[0];
                          $airlineName = $firstFlight['fD']['aI']['name'] ?? 'Unknown Airline';
                          $flightNumber = $firstFlight['fD']['fN'] ?? 'XXX';

                          $key = $airlineName . ' ' . $flightNumber;

                          if (!isset($airlines[$key])) {
                              $airlines[$key] = [
                                  'name' => $airlineName,
                                  'flight' => $flightNumber,
                                  'count' => 1
                              ];
                          } else {
                              $airlines[$key]['count']++;
                          }
                      }
                  }elseif(isset($results['searchResult']['tripInfos']['ONWARD'])){
                  foreach($results['searchResult']['tripInfos']['ONWARD'] as $segment) {
                                  $flightList = $segment['sI'];
                                  $firstFlight = $flightList[0];
                                  $airlineName = $firstFlight['fD']['aI']['name'] ?? 'Unknown Airline';
                                  $flightNumber = $firstFlight['fD']['fN'] ?? 'XXX';

                                  $key = $airlineName . ' ' . $flightNumber;

                                  if (!isset($airlines[$key])) {
                                      $airlines[$key] = [
                                          'name' => $airlineName,
                                          'flight' => $flightNumber,
                                          'count' => 1
                                      ];
                                  } else {
                                      $airlines[$key]['count']++;
                                  }
                              }
                  } elseif(isset($resultsMulticity['searchResult']['tripInfos'])) {
                    foreach($resultsMulticity['searchResult']['tripInfos'] as $segmentGroup) {
                        foreach($segmentGroup as $segment) {
                            $flightList = $segment['sI'];
                            $firstFlight = $flightList[0];
                            $airlineName = $firstFlight['fD']['aI']['name'] ?? 'Unknown Airline';
                            $flightNumber = $firstFlight['fD']['fN'] ?? 'XXX';
                            $key = $airlineName . ' ' . $flightNumber;

                            if (!isset($airlines[$key])) {
                                $airlines[$key] = [
                                    'name' => $airlineName,
                                    'flight' => $flightNumber,
                                    'count' => 1
                                ];
                            } else {
                                $airlines[$key]['count']++;
                            }
                        }
                    }
                }
              @endphp
         @foreach($airlines as $airline)
          <div class="pretty p-default p-thick p-pulse">
              <input type="checkbox" value="{{ $airline['name'] }}-{{ $airline['flight'] }}" class="filter-airline" />
              <div class="state">
                <label>
                  {{ $airline['name'] }} ({{ $airline['flight'] }})
                  <span class="number">{{ $airline['count'] }}</span>
                </label>
              </div>
            </div>
          @endforeach
          </div>


      <div class="sidebar-item">
          <h3>Flight Type</h3>

          @php
              $flightTypes = [];
              if(isset($resultsRound['searchResult']['tripInfos']['ONWARD'])) {
                  foreach($resultsRound['searchResult']['tripInfos']['ONWARD'] as $segment) {
                      $priceList = $segment['totalPriceList'] ?? [];
                      foreach($priceList as $priceItem) {
                          $cabinClassRaw = $priceItem['fd']['ADULT']['cc'] ?? 'N/A';
                          $cabinClass = Str::title(strtolower($cabinClassRaw));

                          if (!isset($flightTypes[$cabinClass])) {
                              $flightTypes[$cabinClass] = 1;
                          } else {
                              $flightTypes[$cabinClass]++;
                          }
                      }
                  }
              }elseif(isset($results['searchResult']['tripInfos']['ONWARD'])){
                 foreach($results['searchResult']['tripInfos']['ONWARD'] as $segment) {
                      $priceList = $segment['totalPriceList'] ?? [];
                      foreach($priceList as $priceItem) {
                          $cabinClassRaw = $priceItem['fd']['ADULT']['cc'] ?? 'N/A';
                          $cabinClass = Str::title(strtolower($cabinClassRaw));

                          if (!isset($flightTypes[$cabinClass])) {
                              $flightTypes[$cabinClass] = 1;
                          } else {
                              $flightTypes[$cabinClass]++;
                          }
                      }
                  }
              } elseif(isset($resultsMulticity['searchResult']['tripInfos'])) {
                foreach($resultsMulticity['searchResult']['tripInfos'] as $segmentGroup) {
                    foreach($segmentGroup as $segment) {
                        foreach(($segment['totalPriceList'] ?? []) as $priceItem) {
                            $cabinClassRaw = $priceItem['fd']['ADULT']['cc'] ?? 'N/A';
                            $cabinClass = Str::title(strtolower($cabinClassRaw));
                            $flightTypes[$cabinClass] = ($flightTypes[$cabinClass] ?? 0) + 1;
                        }
                    }
                }
            }
          @endphp

          @foreach($flightTypes as $type => $count)
            <div class="pretty p-default p-thick p-pulse">
              <input type="checkbox" data-flighttype="{{ $type }}" class="filter-type" value="{{ $type }}"/>
              <div class="state">
                <label>{{ $type }} <span class="number">{{ $count }}</span></label>
              </div>
            </div>
          @endforeach
        </div>
    </div>
  </div>
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

      <div class="tab-content"> <!-- Close button -->

        <!-- Roundtrip Form -->
        <div class="tab-pane fade show active" id="roundtrip">

          <form id="searchFormRoundtrip" action="{{ route('flight.search.roundtrip') }}" method="get">


            <div id="roundtrip" class="tab-pane show active">
              <div class="row filter-box">
                <button id="closeMulticityBtn" class="closeBtn"
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
              <button id="closeMulticityBtn" class="closeBtn"
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
                          <button type="button" class="btn btn-outline-secondary btn-sm traveler-btn" data-type="child" data-action="minus" data-context="oneway">-</button>
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
                          <button type="button" class="btn btn-outline-secondary btn-sm traveler-btn" data-type="infant" data-action="plus" data-context="oneway">+</button>
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
              <button id="closeMulticityBtn" class="closeBtn"
                style="position: absolute; width:60px; top: 10px; right: 10px; background: transparent; border: none; font-size: 24px; cursor: pointer;">
                &times;
              </button>

              <div class="col-lg-3">
                <div class="form-group">
                  <label>Flying From</label>
                  <div class="input-box">
                    <i class="flaticon-placeholder"></i>
                    <div class="niceSelectWrapper input-group">
                      <input hidden value="" name="from_where_multicity_unique[]" id="from_where_multicity_unique">
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
    $durationMinutes = abs($arrivalTime->diffInMinutes($departureTime));
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
         $allFares = [];
        foreach ($priceList as $priceItem) {
            $farePrice = $priceItem['fd']['ADULT']['fC']['TF'] ?? 0;
            $allFares[] = $farePrice;
        }
        $minFare = !empty($allFares) ? min($allFares) : 0;
        $maxFare = !empty($allFares) ? max($allFares) : 0;
        $fareString = implode(',', $allFares); // for data attribute
    @endphp

    <div class="flight-card mb-4 p-3 border rounded shadow-sm bg-white"
    data-stops="{{ $stopCount }}"
          data-airline="{{ $airlineName}}-{{$flightNumber }}"
          data-type="{{ $cabinClass ?? '' }}"
            data-prices="{{ $fareString }}"
          data-min-price="{{ $minFare }}"
          data-max-price="{{ $maxFare }}"
      >
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
          <div style="width: 100%; height: 2px; background-color: orange; position: relative; margin: 6px 0; display: flex; align-items: center; justify-content: end;">
            <span style="color: orange; font-size: 36px; margin-top: -5px; margin-right: -3px;">→</span>
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

          <div class="mb-2 p-2 more-fare {{ $isHidden }}" data-fare-index="{{ $loopIndex }}">

            {{-- Fare info --}}
            <div class="d-flex">
              <input class="form-check-input fare-radio"
                type="radio"
                name="fare_option_{{ $index }}"
                value="{{ $priceItem['id'] ?? '' }}"
                data-fare="{{ $priceItem['fareIdentifier'] ?? '' }}"
                @if($index===0 && $loopIndex===0) checked @endif>

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
          <button type="button"
            class="btn btn-sm w-100 text-white book-btn"
            style="background-color: orange;"
            data-flight="{{ $index }}">
            Book
          </button>


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
            $adultTax = ($fare['ADULT']['fC']['TF'] ?? 0) - $adultBase;
            $totalAdultBase = $adultBase * $adultCount;
            $totalAdultTax = $adultTax * $adultCount;

            // Tax breakdown
            $adultAfC = $fare['ADULT']['afC']['TAF'] ?? [];
            $managementFee = ($adultAfC['MF'] ?? 0);
            $mfTax = ($adultAfC['MFT'] ?? 0);
            $yq = ($adultAfC['YR'] ?? 0);
            $otherTaxes = ($adultAfC['OT'] ?? 0);

            // Child fares
            $childBase = $fare['CHILD']['fC']['BF'] ?? 0;
            $childTax = ($fare['CHILD']['fC']['TF'] ?? 0) - $childBase;
            $totalChildBase = $childBase * $childCount;
            $totalChildTax = $childTax * $childCount;

            // Infant fares
            $infantBase = $fare['INFANT']['fC']['BF'] ?? 0;
            $infantTax = ($fare['INFANT']['fC']['TF'] ?? 0) - $infantBase;
            $totalInfantBase = $infantBase * $infantCount;
            $totalInfantTax = $infantTax * $infantCount;

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
                <tr>
                  <td colspan="3" style="color:#999;">Fare Details for Adult</td>
                </tr>
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
                <tr>
                  <td colspan="3" style="color:#999;">Fare Details for Child</td>
                </tr>
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
                <tr>
                  <td colspan="3" style="color:#999;">Fare Details for Infant</td>
                </tr>
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
    $firstOnwardFlight = $onwardFlights[0]['sI'][0] ?? null;

    $depCity = $firstOnwardFlight['da']['city'] ?? '';
    $arrCity = $firstOnwardFlight['aa']['city'] ?? '';
    $departureDate = isset($firstOnwardFlight['dt']) ? Carbon::parse($firstOnwardFlight['dt'])->format('D, d M y') : '';
    @endphp


    <div class="row g-2">
      <div class="col-lg-6">
        <div class="d-flex">
          <h5>{{ucfirst($depCity)}} to {{ucfirst($arrCity)}}</h5>
          <p class="ms-2"> {{ $departureDate }}</p>

        </div>
        @if(isset($resultsRound['searchResult']['tripInfos']['ONWARD']) && count($resultsRound['searchResult']['tripInfos']['ONWARD']) > 0)
        @foreach($resultsRound['searchResult']['tripInfos']['ONWARD'] as $index => $segment)
        @php
        $flightList = $segment['sI'];
        $firstFlight = $flightList[0];
        $lastFlight = end($flightList);

        $departureTime = Carbon::parse($firstFlight['dt']);
        $arrivalTime = Carbon::parse($lastFlight['at']);
        $durationMinutes = abs($arrivalTime->diffInMinutes($departureTime));
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
        $allFares = [];
        foreach ($priceList as $priceItem) {
            $farePrice = $priceItem['fd']['ADULT']['fC']['TF'] ?? 0;
            $allFares[] = $farePrice;
        }
        $minFare = !empty($allFares) ? min($allFares) : 0;
        $maxFare = !empty($allFares) ? max($allFares) : 0;
        $fareString = implode(',', $allFares); // for data attribute
        @endphp


        <div class="flight-card mb-2 p-3 pb-2 border rounded shadow-sm bg-white"
          data-stops="{{ $stopCount }}"
          data-airline="{{ $airlineName}}-{{$flightNumber }}"
          data-type="{{ $cabinClass ?? '' }}"
            data-prices="{{ $fareString }}"
          data-min-price="{{ $minFare }}"
          data-max-price="{{ $maxFare }}">
          <div class="row g-3 inner-flight-card">

            <div class="col-md-6 col-12">
              <div class="d-flex flex-column justify-content-start gap-3">
                <div class="d-flex justify-content-between gap-3">
                  <div class="d-flex gap-0 flex-column">
                    <img src="{{ $logoUrl }}" class="img-fluid" style="max-height: 30px; max-width: 30px;">
                    <p class="mb-0 fw-semibold text-dark" style="font-size: 14px;">{{ $airlineName }}</p>
                    <small style="font-size: 11px; line-height: 1;">{{ $flightNumber }}</small>
                  </div>
                  <div class="d-flex justify-content-between gap-3">
                    <div class="d-flex gap-0 flex-column align-items-end">
                      <small class="text-muted" style="font-size: 11px; line-height: 1;">{{ $fromCity }}</small>
                      <h5 class="my-1 fw-semibold" style="font-size: 14px;">{{ $fromTime }}</h5>
                      <small class="text-muted" style="font-size: 11px; line-height: 1;">{{ $fromDate }}</small>
                    </div>
                    <div class="d-flex flex-column align-item-center gap-0">
                      <span class="d-block fw-semibold" style="font-size: 14px; line-height: 1.3;">{{ $hours }}h {{ $minutes }}m</span>
                      <div style="width: 100%; height: 2px; background-color: orange; position: relative; margin: 9px 0; display: flex; align-items: center; justify-content: end;">
                        <span style="color: orange; font-size: 36px; margin-top: -6px; margin-right: -3px;">→</span>
                      </div>
                      <small class="text-muted" style="font-size: 11px; line-height: 1;">{{ $stopLabel }}</small>
                    </div>
                    <div class="d-flex gap-0 flex-column align-items-start">
                      <small class="text-muted" style="font-size: 11px; line-height: 1;">{{ $toCity }}</small>
                      <h5 class="my-1 fw-semibold" style="font-size: 14px;">{{ $toTime }}</h5>
                      <small class="text-muted" style="font-size: 11px; line-height: 1;">{{ $toDate }}</small>
                    </div>
                  </div>
                </div>
                <div class="d-flex flex-column gap-2">
                  <button type="button" class="btn btn-sm" style="background-color: #f5f5f5;color: rgb(255, 138, 5);width: fit-content;font-size: 11px;padding: 4px 8px 3px;" onclick="toggleDetails($uniqueId)" data-details-id="flight-details-content-{{ $uniqueId }}">View Details +</button>
                  <span style="color: rgb(255, 138, 5); font-size:11px; line-height: 1; margin-left: 6px">7 Seats Left</span>
                </div>
              </div>
            </div>
            @php
            $containerId = 'fareBlock_' . $index;
            $fareCount = count($priceList);
            $count = 1
            @endphp
            <div class="col-md-6 col-12" id="{{ $containerId }}">
              @foreach($priceList as $loopIndex => $priceItem)
              @php
              $totalFare = $priceItem['fd']['ADULT']['fC']['TF'] ?? 0;
              $tag = $priceItem['fareIdentifier'] ?? 'Standard';
              $cabinClassRaw = $priceItem['fd']['ADULT']['cc'] ?? 'N/A';
              $cabinClass = Str::title(strtolower($cabinClassRaw));
              $isHidden = $loopIndex >= 3 ? 'd-none' : '';
              $isNotLast = $loopIndex < count($priceList) - 1 ? 'border-bottom' : '' ;
                @endphp

                <div class="mx-2 py-2  more-fare  position-relative {{ $isNotLast }} {{ $isHidden }}" data-fare-index="{{ $loopIndex }}">
                <div class="d-flex">
                  <!-- Onward Fare Radio Button -->
                  <input type="radio"
                    name="onward_fare"
                    value="onward_{{ $index }}_{{ $loopIndex }}"
                    data-price-id="{{ $priceItem['id'] }}"
                    data-fare-identifier="{{ $priceItem['fareIdentifier'] }}"
                    {{ $index === 0 && $loopIndex === 0 ? 'checked' : '' }}>

                  <!-- Hidden inputs for Onward Fare -->
                  <input type="hidden" id="onward_fare_id" name="onward_fare_id" value="">
                  <input type="hidden" id="onward_fare_detail" name="onward_fare_detail" value="">

                  <p class="mb-0 ms-2 fw-semibold" style="font-size: 14px;">₹{{ number_format($totalFare, 2) }}</p>
                </div>
                <div class="">
                  <span class="badge bg-warning text-white text-uppercase" style="font-size: 11px; padding: 4px 8px 3px; border-radius: 4px; font-weight: 200;">{{ $tag }}</span>
                  <small class="text-muted" style="font-size: 11px; line-height: 1;">{{ $cabinClass }}</small>
                </div>
                @if($fareCount > 3 && $loopIndex == 2)
                <button class="btn p-1" style="border:1px solid #000;font-size: 11px;color: #ffffff;background: #000;padding: 4px 8px 3px !important;border-radius: 14px;position: absolute;right: 0;bottom: -10px;" onclick="toggleMoreFares('{{ $containerId }}', this)">+More Fares</button>
                @endif
            </div>
            @endforeach

          </div>
          <div class="col-12">
            <div class="flight-details-content mt-2 card" id="flight-details-content-{{ $uniqueId }}" style="display: none;">

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
        </div>
      </div>
      @endforeach
      @endif
    </div>
    <div class="col-lg-6">
      @php
      $lastOnwardFlight = $returnFlights[0]['sI'][0] ?? null;
      $depCity = $lastOnwardFlight['da']['city'] ?? '';
      $arrCity = $lastOnwardFlight['aa']['city'] ?? '';
      $arrivalDate = isset($lastOnwardFlight['at']) ? Carbon::parse($lastOnwardFlight['at'])->format('D, d M y') : '';
      @endphp
      <div class="d-flex">
        <h5>{{ucfirst($depCity)}} to {{ucfirst($arrCity)}}</h5>
        <p class="ms-2"> {{ $arrivalDate }}</p>
      </div>
      @if(isset($resultsRound['searchResult']['tripInfos']['RETURN']) && count($resultsRound['searchResult']['tripInfos']['RETURN']) > 0)
      @foreach($resultsRound['searchResult']['tripInfos']['RETURN'] as $index => $segment)
      @php
      $flightList = $segment['sI'];
      $firstFlight = $flightList[0];
      $lastFlight = end($flightList);

      $departureTime = \Carbon\Carbon::parse($firstFlight['dt']);
      $arrivalTime = \Carbon\Carbon::parse($lastFlight['at']);
      $durationMinutes = abs($arrivalTime->diffInMinutes($departureTime));
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
      $allFares = [];
      foreach ($priceList as $priceItem) {
          $farePrice = $priceItem['fd']['ADULT']['fC']['TF'] ?? 0;
          $allFares[] = $farePrice;
      }
      $minFare = !empty($allFares) ? min($allFares) : 0;
      $maxFare = !empty($allFares) ? max($allFares) : 0;
      $fareString = implode(',', $allFares); // for data attribute
      @endphp

      <div class="flight-card mb-2 p-3 pb-2 border rounded shadow-sm bg-white"
        data-stops="{{ $stopCount }}"
        data-airline="{{ $airlineName}}-{{$flightNumber }}"
        data-type="{{ $cabinClass ?? '' }}"
        data-prices="{{ $fareString }}"
          data-min-price="{{ $minFare }}"
          data-max-price="{{ $maxFare }}">
        <div class="row g-3 inner-flight-card">
          <div class="col-md-6 col-12">
            <div class="d-flex flex-column justify-content-start gap-3">
              <div class="d-flex justify-content-between gap-3">
                <div class="d-flex gap-0 flex-column">
                  <img src="{{ $logoUrl }}" class="img-fluid" style="max-height: 30px; max-width: 30px;">
                  <p class="mb-0 fw-semibold text-dark" style="font-size: 14px;">{{ $airlineName }}</p>
                  <small style="font-size: 11px; line-height: 1;">{{ $flightNumber }}</small>
                </div>
                <div class="d-flex justify-content-between gap-3">
                  <div class="d-flex gap-0 flex-column align-items-end">
                    <small class="text-muted" style="font-size: 11px; line-height: 1;">{{ $fromCity }}</small>
                    <h5 class="my-1 fw-semibold" style="font-size: 14px;">{{ $fromTime }}</h5>
                    <small class="text-muted" style="font-size: 11px; line-height: 1;">{{ $fromDate }}</small>
                  </div>
                  <div class="d-flex flex-column align-item-center gap-0">
                    <span class="d-block fw-semibold" style="font-size: 14px; line-height: 1.3;">{{ $hours }}h {{ $minutes }}m</span>
                    <div style="width: 100%; height: 2px; background-color: orange; position: relative; margin: 9px 0; display: flex; align-items: center; justify-content: end;">
                      <span style="color: orange; font-size: 36px; margin-top: -6px; margin-right: -3px;">→</span>
                    </div>
                    <small class="text-muted" style="font-size: 11px; line-height: 1;">{{ $stopLabel }}</small>
                  </div>
                  <div class="d-flex gap-0 flex-column align-items-start">
                    <small class="text-muted" style="font-size: 11px; line-height: 1;">{{ $toCity }}</small>
                    <h5 class="my-1 fw-semibold" style="font-size: 14px;">{{ $toTime }}</h5>
                    <small class="text-muted" style="font-size: 11px; line-height: 1;">{{ $toDate }}</small>
                  </div>
                </div>
              </div>
              <div class="d-flex flex-column gap-2">
                <button type="button" class="btn btn-sm" style="background-color: #f5f5f5;color: rgb(255, 138, 5);width: fit-content;font-size: 11px;padding: 4px 8px 3px;" onclick="toggleDetails($uniqueId)" data-details-id="flight-details-content-{{ $uniqueId }}">View Details +</button>
                <span style="color: rgb(255, 138, 5); font-size:11px; line-height: 1; margin-left: 6px">7 Seats Left</span>
              </div>
            </div>
          </div>

          @php
          $containerId = 'return_fareBlock_' . $index;
          $fareCount = count($priceList);
          @endphp
          <div class="col-md-6 col-12 text-start" id="{{ $containerId }}">
            @foreach($priceList as $loopIndex => $priceItem)
            @php
            $totalFare = $priceItem['fd']['ADULT']['fC']['TF'] ?? 0;
            $tag = $priceItem['fareIdentifier'] ?? 'Standard';
            $cabinClassRaw = $priceItem['fd']['ADULT']['cc'] ?? 'N/A';
            $cabinClass = \Illuminate\Support\Str::title(strtolower($cabinClassRaw));
            $isHidden = $loopIndex >= 3 ? 'd-none' : '';
            $isNotLast = $loopIndex < count($priceList) - 1 ? 'border-bottom' : '' ;
              @endphp

              <div class="mx-2 py-2  more-fare  position-relative {{ $isNotLast }} {{ $isHidden }}" data-fare-index="{{ $loopIndex }}">
              <div class="d-flex">
                <!-- Return Fare Radio Button -->
                <!-- Return Fare Radio Button -->
                <input type="radio"
                  name="return_fare"
                  value="return_{{ $index }}_{{ $loopIndex }}"
                  data-price-id="{{ $priceItem['id'] }}"
                  data-fare-identifier="{{ $priceItem['fareIdentifier'] }}"
                  {{ $index === 0 && $loopIndex === 0 ? 'checked' : '' }}>

                <!-- Hidden inputs for Return Fare -->
                <input type="hidden" id="return_fare_id" name="return_fare_id" value="">
                <input type="hidden" id="return_fare_detail" name="return_fare_detail" value="">

                <p class="mb-0 ms-2 fw-semibold" style="font-size: 14px;">₹{{ number_format($totalFare, 2) }}</p>
              </div>
              <div class="">
                <span class="badge bg-warning text-white text-uppercase" style="font-size: 11px; padding: 4px 8px 3px; border-radius: 4px; font-weight: 200;">{{ $tag }}</span>
                <small class="text-muted" style="font-size: 11px; line-height: 1;">{{ $cabinClass }}</small>
              </div>
              @if($fareCount > 3 && $loopIndex == 2)
              <button class="btn p-1" style="border:1px solid #000;font-size: 11px;color: #ffffff;background: #000;padding: 4px 8px 3px !important;border-radius: 14px;position: absolute;right: 0;bottom: -10px;" onclick="toggleMoreFares('{{ $containerId }}', this)">+More Fares</button>
              @endif
          </div>
          @endforeach
        </div>

        <div class="col-12">
          <div class="flight-details-content mt-2 card" id="flight-details-content-{{ $uniqueId }}" style="display: none;">

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

      </div>

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
  @if($segments)
  <ul class="segment-tabs d-flex flex-nowrap">
    @foreach($segments as $index => $seg)
    <li class="{{ $index === 0 ? 'active' : '' }}" onclick="showSegment({{ $index }})">
      {{ $seg['label'] }}
      <small>{{ $seg['date'] }}</small>
    </li>

    @endforeach
  </ul>
  @endif
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
      $stopovers[]=$segments[$i]['aa'];
      }
      }

      $stopCount=count($stopovers);
      $stopLabel=$stopCount===0 ? 'Non-stop' : "{$stopCount} stop" . ($stopCount> 1 ? 's' : '');

      $tooltipHTML = "<div class='card' style='width: 200px; padding: 10px; background-color: #fff; color: #000; border: 1px solid #ccc;'>
        <div class='card-body'>";
          foreach ($stopovers as $stop) {
          $city = $stop['city'] ?? '';
          $code = $stop['code'] ?? '';
          $name = $stop['name'] ?? '';
          $tooltipHTML .= "<div class='mb-2'><strong>{$city} ({$code})</strong><br><small>{$name}</small></div>
          <hr style='margin: 5px 0;'>";
          }
          $tooltipHTML .= "
        </div>
      </div>";

      $class = strtoupper($priceData['ADULT']['cc'] ?? 'ECONOMY');
      $refundable = ($priceData['ADULT']['rT'] ?? 0) == 1 ? 'Refundable' : 'Non-refundable';
      $passengerTypes = ['ADULT' => 'Adult', 'CHILD' => 'Child', 'INFANT' => 'Infant'];
        $allFares = [];
            foreach ($combo['totalPriceList'] ?? [] as $priceItem) {
                $farePrice = $priceItem['fd']['ADULT']['fC']['TF'] ?? 0;
                if ($farePrice > 0) {
                    $allFares[] = $farePrice;
                }
            }
            $minFare = !empty($allFares) ? min($allFares) : 0;
            $maxFare = !empty($allFares) ? max($allFares) : 0;
            $fareString = implode(',', $allFares);

            // Airline
            $airlineName = $airline['name'] ?? 'Unknown Airline';
            $flightNumber = $firstSeg['fD']['fN'] ?? 'XXX';

            // Cabin class
            $cabinClassRaw = $priceData['ADULT']['cc'] ?? 'N/A';
            $cabinClass = \Illuminate\Support\Str::title(strtolower($cabinClassRaw));
            @endphp

      {{-- Keep frontend from your flight-card or flight-multicity structure --}}
     <div class="flight-multicity border rounded shadow-sm mb-4 p-3 bg-white"
            data-stops="{{ $stopCount }}"
            data-airline="{{ $airlineName }}-{{ $flightNumber }}"
            data-type="{{ $cabinClass }}"
            data-prices="{{ $fareString }}"
            data-min-price="{{ $minFare }}"
            data-max-price="{{ $maxFare }}">
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
              <li class="tab-items active px-3 py-2" style="cursor: pointer;"
                onclick="switchSubTab(this, 'flightDetailsContent_{{ $index }}_{{ $comboIndex }}')">
                <small>Flight Details</small>
              </li>

              <li class="tab-items px-3 py-2" style="cursor: pointer;"
                onclick="switchSubTab(this, 'fareDetailsContent_{{ $index }}_{{ $comboIndex }}')">
                <small>Fare Details</small>
              </li>
              <li class="tab-items px-3 py-2" style="cursor: pointer;"
                onclick="switchSubTab(this, 'fareRulesContent_{{ $index }}_{{ $comboIndex }}')">
                <small>Fare Rules</small>
              </li>
              <li class="tab-items px-3 py-2" style="cursor: pointer;"
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
                  {{ $fromCity }} → {{ $toCity }}
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
              $className = Str::title(strtolower($classCode)); // Properly formatted: "Economy"

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
              $adultTax = ($fare['ADULT']['fC']['TF'] ?? 0) - $adultBase;

              $totalAdultBase = $adultBase * $adultCount;
              $totalAdultTax = $adultTax * $adultCount;

              // Tax breakdown for Adult
              $adultAfC = $fare['ADULT']['afC']['TAF'] ?? [];
              $managementFee = ($adultAfC['MF'] ?? 0);
              $mfTax = ($adultAfC['MFT'] ?? 0);
              $yq = ($adultAfC['YR'] ?? 0);
              $otherTaxes = ($adultAfC['OT'] ?? 0);

              // Child fares
              $childBase = $fare['CHILD']['fC']['BF'] ?? 0;
              $childTax = ($fare['CHILD']['fC']['TF'] ?? 0) - $childBase;

              $totalChildBase = $childBase * $childCount;
              $totalChildTax = $childTax * $childCount;

              // Infant fares
              $infantBase = $fare['INFANT']['fC']['BF'] ?? 0;
              $infantTax = ($fare['INFANT']['fC']['TF'] ?? 0) - $infantBase;

              $totalInfantBase = $infantBase * $infantCount;
              $totalInfantTax = $infantTax * $infantCount;

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
                  <tr>
                    <td colspan="3" style="color:#999; border: none;">Fare Details for Adult</td>
                  </tr>
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
                  <tr>
                    <td colspan="3" style="color:#999; border: none;">Fare Details for Child</td>
                  </tr>
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
                  <tr>
                    <td colspan="3" style="color:#999; border: none;">Fare Details for Infant</td>
                  </tr>
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
              <button onclick="closeDetails(this)" style="position: absolute; top: 10px; right: 10px; border: none; background: none; font-size: 16px; cursor: pointer;">✖</button>
              <p>Tickets are non-refundable. Change fee applicable.</p>
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



  </div>
  <div id="bottom-itinerary-bar" class="d-none"
    style="position: sticky; left: 0; bottom: 0;  width: 100%; height:auto; min-height:90px; background-color: #1a1a1a; color: white; z-index: 9999; padding: 10px 20px; box-shadow: 0 -2px 8px rgba(0,0,0,0.3); font-size: 13px; display:flex; align-items:center; justify-content:center;">

    <div id="onward-summary" class="d-flex align-items-center me-4" style="margin-left:30px;"></div>
    <div id="return-summary" class="d-flex align-items-center me-4"></div>

    <div id="total-fare" class="fw-bold text-white me-4"></div>

    <button type="button" class="btn btn-sm text-white fw-bold  book-btn-round"
      style="background-color:orange; padding:10px 20px; border-radius:4px;">
      BOOK
    </button>

  </div>

  <!-- ✨ Move this outside any container -->
  <div id="bottom-itinerary-bar" class="d-none"
    style="position: sticky; left: 0; bottom: 0; width: 100%; height:15%; background-color: #1a1a1a; color: white; z-index: 9999; padding: 10px 20px; box-shadow: 0 -2px 8px rgba(0,0,0,0.3); font-size: 13px; overflow-x: auto; white-space: nowrap;">
    <div id="route-details"
      style="display: flex; flex-direction: row; gap: 20px; overflow-x: auto; white-space: nowrap; flex-grow: 1;">
    </div>
    <br>
    <button class="btn"
      style="position: absolute; background-color:orange;  right: 20px; top: 50%; transform: translateY(-50%);">BOOK</button>
  </div>

</section>
<!-- flight-list ends -->
<script src="{{ asset('js/flight-list.js') }}"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const filters = document.querySelectorAll(".filter-stop, .filter-airline, .filter-type");
    const flightCards = document.querySelectorAll(".flight-card, .flight-multicity");

    let selectedMinPrice = parseFloat(@json($globalMinPrice));
    let selectedMaxPrice = parseFloat(@json($globalMaxPrice));

    // ✅ Init jQuery UI slider
    $("#priceRange").slider({
        range: true,
        min: selectedMinPrice,
        max: selectedMaxPrice,
        values: [selectedMinPrice, selectedMaxPrice],
        slide: function (event, ui) {
            selectedMinPrice = ui.values[0];
            selectedMaxPrice = ui.values[1];

            document.querySelector(".min-value").innerText = selectedMinPrice.toLocaleString() + " ₹";
            document.querySelector(".max-value").innerText = selectedMaxPrice.toLocaleString() + " ₹";

            applyFilters();
        }
    });

    function applyFilters() {
        const selectedStops = Array.from(document.querySelectorAll(".filter-stop:checked")).map(cb => cb.value.toLowerCase());
        const selectedAirlines = Array.from(document.querySelectorAll(".filter-airline:checked")).map(cb => cb.value.toLowerCase());
        const selectedTypes = Array.from(document.querySelectorAll(".filter-type:checked")).map(cb => cb.value.toLowerCase());

        let anyVisible = false;

        flightCards.forEach(card => {
            const cardStops = card.getAttribute("data-stops") || "";
            const cardAirline = card.getAttribute("data-airline")?.toLowerCase() || "";
            const cardType = card.getAttribute("data-type")?.toLowerCase() || "";

            const prices = (card.getAttribute("data-prices") || "")
                            .split(",")
                            .map(Number)
                            .filter(p => !isNaN(p));
            const priceMatch = prices.some(price => price >= selectedMinPrice && price <= selectedMaxPrice);

            const stopMatch = selectedStops.length === 0 || selectedStops.includes(cardStops);
            const airlineMatch = selectedAirlines.length === 0 || selectedAirlines.includes(cardAirline);
            const typeMatch = selectedTypes.length === 0 || selectedTypes.includes(cardType);

            if (stopMatch && airlineMatch && typeMatch && priceMatch) {
                card.style.display = "block";
                anyVisible = true;
            } else {
                card.style.display = "none";
            }
        });

        let msg = document.getElementById("noResultsMsg");
        if (!anyVisible) {
            if (!msg) {
                msg = document.createElement("div");
                msg.id = "noResultsMsg";
                msg.innerHTML = "<p class='text-center text-danger fw-bold mt-3'>No flights found.</p>";
                document.querySelector(".flight-list .container").appendChild(msg);
            }
        } else {
            if (msg) msg.remove();
        }
    }

    filters.forEach(f => f.addEventListener("change", applyFilters));
});


</script>


<script>
  document.addEventListener("DOMContentLoaded", function() {
    const onwardFlights = @json($onwardFlights);
    const returnFlights = @json($returnFlights);

    const itineraryBar = document.getElementById("bottom-itinerary-bar");
    const onwardSummary = document.getElementById("onward-summary");
    const returnSummary = document.getElementById("return-summary");
    const totalFareDiv = document.getElementById("total-fare");
    console.log(onward_fare_id);
    let onwardFareValue = null;
    let returnFareValue = null;

    function formatSegment(segment, totalFare) {
      const first = segment.sI[0];
      const last = segment.sI.slice(-1)[0];
      const airlineName = first.fD.aI.name;
      const airlineCode = first.fD.aI.code;
      const flightNumber = first.fD.fN;
      const logoUrl = `/AirlinesLogo/${airlineCode}.png`;

      const depTime = new Date(first.dt).toLocaleTimeString([], {
        hour: '2-digit',
        minute: '2-digit'
      });
      const arrTime = new Date(last.at).toLocaleTimeString([], {
        hour: '2-digit',
        minute: '2-digit'
      });

      const depCity = first.da.city ?? first.da.code;
      const arrCity = last.aa.city ?? last.aa.code;

      return `
        <div class="d-flex align-items-center">
            <!-- Airline Info -->
            <div class="pe-3 text-center">
                <div class="d-flex align-items-center">
                    <img src="${logoUrl}" onerror="this.src='/AirlinesLogo/default.png'" alt="${airlineName}" style="height:24px;">
                    <div class="fw-bold ps-2">${airlineName}</div>
                </div>
                <div>${airlineCode}-${flightNumber}</div>
            </div>

            <!-- Flight Times + Cities -->
            <div class="pe-3">
                <div>${depTime} → ${arrTime}</div>
                <div>${depCity} → ${arrCity}</div>
            </div>


            <!-- Price -->
            <div class="fw-bold">₹${totalFare.toLocaleString('en-IN')}</div>
             <!-- Vertical Separator -->
            <div class="border-start mx-3" style="height:40px;"></div>
        </div>

    `;
    }


    function updateBar() {
      if (!onwardFareValue || !returnFareValue) {
        itineraryBar.classList.add("d-none");
        return;
      }

      // Parse onward
      const [_, oIndex, oFareIndex] = onwardFareValue.split("_");
      const onwardSegment = onwardFlights[oIndex];
      const onwardFare = onwardSegment.totalPriceList[oFareIndex];
      const onwardPrice = onwardFare.fd.ADULT.fC.TF ?? 0;

      // Parse return
      const [__, rIndex, rFareIndex] = returnFareValue.split("_");
      const returnSegment = returnFlights[rIndex];
      const returnFare = returnSegment.totalPriceList[rFareIndex];
      const returnPrice = returnFare.fd.ADULT.fC.TF ?? 0;

      const totalPrice = onwardPrice + returnPrice;

      // Onward Flight
      onwardSummary.innerHTML = `
            <div class="pb-2 border-bottom">
                <h6 class="fw-bold mb-1">Onward Flight</h6>
                ${formatSegment(onwardSegment, onwardPrice)}
            </div>
        `;

      // Return Flight
      returnSummary.innerHTML = `
            <div class="pt-2 pb-2 border-bottom">
                <h6 class="fw-bold mb-1">Return Flight</h6>
                ${formatSegment(returnSegment, returnPrice)}
            </div>
        `;

      // Total + Book Button
      totalFareDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mt-2">
                <span class="fw-bold fs-5">Total: ₹${totalPrice.toLocaleString('en-IN')}</span>
            </div>
        `;

      itineraryBar.classList.remove("d-none");
    }

    // Onward fare selection
    document.querySelectorAll('input[name="onward_fare"]').forEach(radio => {
      radio.addEventListener("change", function() {
        onwardFareValue = this.value;
        updateBar();
      });
    });

    // Return fare selection
    document.querySelectorAll('input[name="return_fare"]').forEach(radio => {
      radio.addEventListener("change", function() {
        returnFareValue = this.value;
        updateBar();
      });
    });
    // ✅ Auto-select already checked options (so bar shows immediately)
    const defaultOnward = document.querySelector('input[name="onward_fare"]:checked');
    const defaultReturn = document.querySelector('input[name="return_fare"]:checked');

    if (defaultOnward) onwardFareValue = defaultOnward.value;
    if (defaultReturn) returnFareValue = defaultReturn.value;

    // Always call once on load
    updateBar();
  });
</script>

<script>
  // function toggleDetails(button) {
  //   const content = button.nextElementSibling;
  //   const isVisible = content.style.display === 'block';

  //   content.style.display = isVisible ? 'none' : 'block';
  //   button.textContent = isVisible ? 'View Details +' : 'Hide Details -';
  // }

  function toggleDetails(uniqueId) {
    alert(uniqueId);
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
  window.addEventListener("DOMContentLoaded", function() {
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
    const rtDiv = document.getElementById("roundtrip-summary-display");

    // Hide all by default
    if (mcDiv) mcDiv.style.display = "none";
    if (owDiv) owDiv.style.display = "none";
    if (rtDiv) rtDiv.style.display = "none";

    // Detect multicity (same as your existing code)
    const isMulticity = getAllParamsByPrefix("from_where_multicity_unique").length > 0;

    // Detect oneway
    const isOneway = urlParams.get("from_where_oneway[]") !== null;

    // Detect roundtrip based on your URL params
    // Adjust this to match your actual URL keys:
    const isRoundtrip =
      urlParams.get("from_where[]") !== null &&
      urlParams.get("to_where[]") !== null &&
      urlParams.get("depart_date") !== null &&
      urlParams.get("return_date") !== null;

    // Render Multicity summary (unchanged)
    if (isMulticity && mcDiv) {
      // Your existing multicity code here (no change)
      const fromCodes = getAllParamsByPrefix("from_where_multicity_unique");
      const toCodes = getAllParamsByPrefix("to_where_multicity_unique");
      const fromCities = getAllParamsByPrefix("from_where_text_multicity");
      const toCities = getAllParamsByPrefix("to_where_text_multicity");
      const dates = getAllParamsByPrefix("departure_date_multicity_unique");

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
        <button class="btn btn-outline-light btn-sm" onclick="toggleFilterOffcanvas()">FILTER</button>
      </div>
    </div>`;

      mcDiv.innerHTML = outputHtml;
      mcDiv.style.display = "block";
    }

    // Render Oneway summary (unchanged)
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
        <button class="btn btn-outline-light btn-sm" onclick="toggleFilterOffcanvas()">FILTER</button>
        </div>
      </div>`;

      owDiv.innerHTML = outputHtml;
      owDiv.style.display = "block";
    }

    // Render Roundtrip summary (UPDATED keys to your params)
    else if (isRoundtrip && rtDiv) {
      const fromCodes = urlParams.getAll("from_where[]") || [];
      const toCodes = urlParams.getAll("to_where[]") || [];
      const depDate = urlParams.get("depart_date") || "";
      const retDate = urlParams.get("return_date") || "";
      const travelClass = urlParams.get("travel_class") || "Economy";
      const adult = urlParams.get("adults") || "1";
      const child = urlParams.get("children") || "0";
      const infant = urlParams.get("infants") || "0";
      const preferredAirline = "None";

      const passengerSummary = `${adult} Adult${child > 0 ? `, ${child} Child` : ""}${infant > 0 ? `, ${infant} Infant` : ""} | ${travelClass}`;

      let flightsHtml = "";
      for (let i = 0; i < fromCodes.length; i++) {
        flightsHtml += `
      <div class="d-flex align-items-center me-4">
        <div class="text-center me-2">
          <div><strong>${fromCodes[i]}</strong></div>
        </div>
        <div class="mx-2">✈</div>
        <div class="text-center me-2">
          <div><strong>${toCodes[i]}</strong></div>
        </div>
      </div>
      ${i < fromCodes.length - 1 ? `<div class="vr mx-2" style="height: 40px;"></div>` : ""}
    `;
      }

      const outputHtml = `
    <div class="multicity-summary-container px-4 d-flex flex-wrap align-items-center justify-content-center px-3 py-2 text-white" style="background-color:#2f2f2f;">
      ${flightsHtml}
      <div class="vr mx-2" style="height: 40px;"></div>
      <div class="me-4">
        <strong>Departure Date</strong><br>
        <small>${depDate}</small>
      </div>
      <div class="vr mx-2" style="height: 40px;"></div>
      <div class="me-4">
        <strong>Return Date</strong><br>
        <small>${retDate}</small>
      </div>
      <div class="vr mx-2" style="height: 40px;"></div>
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
        <button class="btn btn-outline-light btn-sm" onclick="toggleSearchTab('rt')">MODIFY SEARCH</button>
        <button class="btn btn-outline-light btn-sm" onclick="toggleFilterOffcanvas()">FILTER</button>
      </div>
    </div>`;

      rtDiv.innerHTML = outputHtml;
      rtDiv.style.display = "block";
    }

  });

  function toggleFilterOffcanvas() {
    $('body').css('overflow', 'hidden');
    $('#filterOffcanvas').addClass('active');
  }

  function closeFilterOffcanvas() {
    $('body').css('overflow', 'auto');
    $('#filterOffcanvas').removeClass('active');
  }
</script>

<script>
  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(tooltipTriggerEl) {
    new bootstrap.Tooltip(tooltipTriggerEl);
  });
</script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const flightData = @json($tripInfosmulticity);
    const radios = document.querySelectorAll('input[name="selected_fare"]');
    const itineraryBar = document.getElementById('bottom-itinerary-bar');
    const routeDetails = document.getElementById('route-details');

    radios.forEach((radio) => {
      radio.addEventListener('change', function() {
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
          const depTime = new Date(seg.dt).toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit'
          });
          const arrTime = new Date(seg.at).toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit'
          });

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
      <div class="item">
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
<script>
  document.querySelectorAll('.book-btn').forEach(button => {
    button.addEventListener('click', function() {
      let flightIndex = this.dataset.flight;
      let selectedRadio = document.querySelector(`input[name="fare_option_${flightIndex}"]:checked`);

      if (!selectedRadio) {
        alert("Please select a fare option first.");
        return;
      }

      let priceId = selectedRadio.value; // itineraryId goes into {priceId}

      let fareIdentifier = selectedRadio.dataset.fare;
      let url = `{{ route('review', ['priceId' => '__PRICE_ID__']) }}?fareIdentifier=${encodeURIComponent(fareIdentifier)}`;
      url = url.replace('__PRICE_ID__', encodeURIComponent(priceId));


      window.location.href = url;
    });
  });
</script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    function updateHiddenInputs() {
      let onwardFare = document.querySelector("input[name='onward_fare']:checked");
      let returnFare = document.querySelector("input[name='return_fare']:checked");

      if (onwardFare) {
        document.getElementById("onward_fare_id").value = onwardFare.dataset.priceId;
        document.getElementById("onward_fare_detail").value = onwardFare.dataset.fareIdentifier;
      }

      if (returnFare) {
        document.getElementById("return_fare_id").value = returnFare.dataset.priceId;
        document.getElementById("return_fare_detail").value = returnFare.dataset.fareIdentifier;
      }
    }

    // Run on page load
    updateHiddenInputs();

    // Update when user changes selection
    document.querySelectorAll("input[name='onward_fare'], input[name='return_fare']").forEach(el => {
      el.addEventListener("change", updateHiddenInputs);
    });

    // Book button click
    document.querySelector(".book-btn-round").addEventListener("click", function() {
      let onwardPriceId = document.getElementById("onward_fare_id").value;
      let returnPriceId = document.getElementById("return_fare_id").value;
      let onwardFareIdentifier = document.getElementById("onward_fare_detail").value;
      let returnFareIdentifier = document.getElementById("return_fare_detail").value;

      if (!onwardPriceId || !returnPriceId) {
        alert("Please select both onward and return flights.");
        return;
      }

      let url = `{{ route('review.round') }}?` +
        `onwardPriceId=${encodeURIComponent(onwardPriceId)}` +
        `&returnPriceId=${encodeURIComponent(returnPriceId)}` +
        `&onwardFareIdentifier=${encodeURIComponent(onwardFareIdentifier)}` +
        `&returnFareIdentifier=${encodeURIComponent(returnFareIdentifier)}`;

      window.location.href = url;
    });
  });
</script>


<script>
  // Update hidden input when an onward fare is selected
  document.querySelectorAll('input[name="onward_fare"]').forEach(radio => {
    radio.addEventListener('change', function() {
      // Set the hidden input value
      document.getElementById('onward_fare_id').value = this.value;
      console.log('Updated Onward Fare ID:', this.value);
    });
  });

  // Update hidden input when a return fare is selected
  document.querySelectorAll('input[name="return_fare"]').forEach(radio => {
    radio.addEventListener('change', function() {
      document.getElementById('return_fare_id').value = this.value;
      console.log('Updated Return Fare ID:', this.value);
    });
  });
</script>
<script>
  document.querySelectorAll('input[name="return_fare"]').forEach(radio => {
    radio.addEventListener('change', function() {
      // Copy price_id and fare_identifier into hidden inputs
      document.getElementById('return_fare_id').value = this.dataset.priceId;
      document.getElementById('return_fare_detail').value = this.dataset.fareIdentifier;

      console.log('Selected Return Fare:', this.value);
      console.log('Price ID:', this.dataset.priceId);
      console.log('Fare Identifier:', this.dataset.fareIdentifier);
    });
  });

  // Also trigger once on page load to set default selection
  const checkedReturn = document.querySelector('input[name="return_fare"]:checked');
  if (checkedReturn) {
    document.getElementById('return_fare_id').value = checkedReturn.dataset.priceId;
    document.getElementById('return_fare_detail').value = checkedReturn.dataset.fareIdentifier;
  }
  // Update hidden inputs when onward fare is selected
  document.querySelectorAll('input[name="onward_fare"]').forEach(radio => {
    radio.addEventListener('change', function() {
      document.getElementById('onward_fare_id').value = this.dataset.priceId;
      document.getElementById('onward_fare_detail').value = this.dataset.fareIdentifier;

      console.log('Selected Onward Fare:', this.value);
      console.log('Price ID:', this.dataset.priceId);
      console.log('Fare Identifier:', this.dataset.fareIdentifier);
    });
  });

  // Also set hidden inputs on page load (default checked radio)
  const checkedOnward = document.querySelector('input[name="onward_fare"]:checked');
  if (checkedOnward) {
    document.getElementById('onward_fare_id').value = checkedOnward.dataset.priceId;
    document.getElementById('onward_fare_detail').value = checkedOnward.dataset.fareIdentifier;
  }
</script>

@endsection
