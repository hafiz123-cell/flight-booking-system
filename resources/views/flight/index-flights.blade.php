@extends('layout_new.layout')
<style>
  .slide-image {
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: 0 0;
    background-repeat: no-repeat;
    transition: background-position 0.5s ease-in-out;
  }

  .animation-on {
    animation: airplaneMove 15s linear infinite;
  }

  @keyframes airplaneMove {
    0% {
      background-position: 0 0;
    }

    50% {
      background-position: 100% 0;
      /* Move horizontally like an airplane */
    }

    100% {
      background-position: 0 0;
      /* Return to start */
    }
  }
</style>
@section('content')
<!-- Success Alert -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 1050; width: 320px;">
  {{ session('success') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Error Alert -->
@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 mt-5 m-3" style="z-index: 1050; width: 320px;">
  <ul class="mb-0 ps-3">
    @foreach($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- On Load Modal -->
<!-- Modal -->


<!-- banner starts -->
<section class="banner ver-banner">
  <div class="slide-image animation-on" style="background-image: url(&quot;{{ asset('images/slider6.jpg') }}&quot;)"></div>

  <div class="swiper-content">
    <h1>Hurry up! <span>Book a Ticket</span> & Just Leave</h1>
    <p class="mar-bottom-30">Foresee the pain and trouble that are bound to ensue and equal fail in their duty through weakness.</p>
    <a href="" class="mar-left-10 p-3" style="background-color:#ec7b34; color:white; border-radius: 5px;">Book Now</a>
  </div>
  <div class="overlay"></div>
</section>
<!-- banner ends -->
<section class="banner-form form-style2">
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

      <div class="tab-content">
        <!-- Roundtrip Form -->
        <div class="tab-pane fade show active" id="roundtrip">
          <form id="searchFormRoundtrip" action="{{ route('flight.search.roundtrip') }}" method="get">


            <div id="roundtrip" class="tab-pane show active">
              <div class="row filter-box">

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
                    <button type="submit" class="biz-btn" style="background-color:#ec7b34;"><i class="fa fa-search"></i> Find Now</button>
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
                  <button type="submit" class="biz-btn" style="background-color:#ec7b34;" id="findFlightBtn"><i class="fa fa-search"></i> Find Now</button>
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
                  <button type="submit" class="biz-btn" style="background-color:#ec7b34;" id="multicityFindFlightBtnUnique"><i class="fa fa-search"></i> Find Now</button>
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
                  <button type="button" class="biz-btn add-segment-btn w-60" style="margin-top: 35px; background-color:#ec7b34; width:130px;" onclick="addSegmentRow()">
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
<!-- form starts -->

<!-- form ends -->

<!-- form ends -->
<!-- top deal starts -->
<section class="top-deals">
  <div class="container">
    <div class="row display-flex">
      <div class="col-lg-8">
        <div class="section-title title-full width100">
          <h2>Offers for you </h2>
          <p>
            Discover the best holiday packages that fit your budget for your favourite destinations.
          </p>
        </div>
      </div>
      <div class="col-lg-4">
        <a href="#" class="biz-btn biz-btn1 pull-right"> Get More Deals</a>
      </div>
    </div>
    <div class="top-deal-main">
      <div class="row">
        <div class="col-lg-4 col-sm-6 mar-bottom-30">
          <div class="slider-item">
            <div class="slider-image">
              <img src="{{asset('images_gofly/trending7.jpg')}}" alt="image" />
            </div>
            <div class="slider-content">
              <!-- <h6 class="mar-bottom-10"><i class="fa fa-map-marker-alt"></i> United Kingdom</h6> -->
              <h4 class="mar-bottom-5">Up to 20% Discount!</h4>
              <!-- <div class="rate-rev mar-bottom-20">
                                    <span class="num-rating mar-right-5">4.6/5</span>
                                    <span class="review">(166 reviews)</span>
                                </div> -->
              <p>Enjoy Upto 20% Discount on Your First Holiday Booking!</p>
              <button class="book-btn book-now-btn" data-plan-id="special-offer-1" data-plan-name="Special Discount Offer">BOOK NOW</button>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-sm-6 mar-bottom-30">
          <div class="slider-item">
            <div class="slider-image">
              <img src="{{asset('images_gofly/trending8.jpg')}}" alt="image" />
            </div>
            <div class="slider-content">
              <!-- <h6 class="mar-bottom-10"><i class="fa fa-map-marker-alt"></i> Thailand</h6> -->
              <h4 class="mar-bottom-5">Up to 20% Discount!</h4>
              <!-- <div class="rate-rev mar-bottom-20">
                                    <span class="num-rating mar-right-5">4.6/5</span>
                                    <span class="review">(166 reviews)</span>
                                </div> -->
              <p>Enjoy Upto 20% Discount on Your First Holiday Booking!</p>
              <button class="book-btn book-now-btn" data-plan-id="special-offer-2" data-plan-name="Special Discount Offer">BOOK NOW</button>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-sm-6 mar-bottom-30">
          <div class="slider-item">
            <div class="slider-image">
              <img src="{{asset('images_gofly/trending9.jpg')}}" alt="image" />
            </div>
            <div class="slider-content">
              <!-- <h6 class="mar-bottom-10"><i class="fa fa-map-marker-alt"></i> South Korea</h6> -->
              <h4 class="mar-bottom-5">Up to 20% Discount!</h4>
              <!-- <div class="rate-rev mar-bottom-20">
                                    <span class="num-rating mar-right-5">4.6/5</span>
                                    <span class="review">(166 reviews)</span>
                                </div> -->
              <p>Enjoy Upto 20% Discount on Your First Holiday Booking!</p>
              <button class="book-btn book-now-btn" data-plan-id="special-offer-3" data-plan-name="Special Discount Offer">BOOK NOW</button>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-sm-6">
          <div class="slider-item">
            <div class="slider-image">
              <img src="{{asset('images_gofly/trending10.jpg')}}" alt="image" />
            </div>
            <div class="slider-content">
              <!-- <h6 class="mar-bottom-10"><i class="fa fa-map-marker-alt"></i> Germany</h6> -->
              <h4 class="mar-bottom-5">Up to 20% Discount!</h4>
              <!-- <div class="rate-rev mar-bottom-20">
                                    <span class="num-rating mar-right-5">4.6/5</span>
                                    <span class="review">(166 reviews)</span>
                                </div> -->
              <p>Enjoy Upto 20% Discount on Your First Holiday Booking!</p>
              <button class="book-btn book-now-btn" data-plan-id="special-offer-4" data-plan-name="Special Discount Offer">BOOK NOW</button>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-sm-6">
          <div class="slider-item">
            <div class="slider-image">
              <img src="{{asset('images_gofly/trending6.jpg')}}" alt="image" />
            </div>
            <div class="slider-content">
              <!-- <h6 class="mar-bottom-10"><i class="fa fa-map-marker-alt"></i> Mexico</h6> -->
              <h4 class="mar-bottom-5">Up to 20% Discount!</h4>
              <!-- <div class="rate-rev mar-bottom-20">
                                    <span class="num-rating mar-right-5">4.6/5</span>
                                    <span class="rePassesview">(166 reviews)</span>
                                </div> -->
              <p>Enjoy Upto 20% Discount on Your First Holiday Booking! </p>
              <button class="book-btn book-now-btn" data-plan-id="special-offer-5" data-plan-name="Special Discount Offer">BOOK NOW</button>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-sm-6">
          <div class="slider-item">
            <div class="slider-image">
              <img src="{{asset('images_gofly/trending5.jpg')}}" alt="image" />
            </div>
            <div class="slider-content">
              <!-- <h6 class="mar-bottom-10"><i class="fa fa-map-marker-alt"></i> Nepal</h6> -->
              <h4 class="mar-bottom-5">Up to 20% Discount!</h4>
              <!-- <div class="rate-rev mar-bottom-20">
                                    <span class="num-rating mar-right-5">4.6/5</span>
                                    <span class="review">(166 reviews)</span>
                                </div> -->
              <p>Enjoy Upto 20% Discount on Your First Holiday Booking!</p>
              <button class="book-btn book-now-btn" data-plan-id="special-offer-6" data-plan-name="Special Discount Offer">BOOK NOW</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- top deal ends -->

<!-- why us starts -->
<section class="why-us">
  <div class="container">
    <div class="why-us-box">
      <div class="row">
        <div class="col-lg-3 col-sm-6">
          <div class="why-us-item why-us-item1 text-center">
            <div class="why-us-icon">
              <i class="flaticon-call"></i>
            </div>
            <div class="why-us-content">
              <h4>Advice & Support</h4>
              <p class="mar-0">Count on us for expert travel advice and round the click support to make your journey smooth and stress-free</p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-sm-6">
          <div class="why-us-item why-us-item1 text-center">
            <div class="why-us-icon">
              <i class="flaticon-global"></i>
            </div>
            <div class="why-us-content">
              <h4>Air Ticketing</h4>
              <p class="mar-0">Grab the best deals on domestic and international flights with our quick and easy air tricketing services</p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-sm-6">
          <div class="why-us-item why-us-item1 text-center">
            <div class="why-us-icon">
              <i class="flaticon-building"></i>
            </div>
            <div class="why-us-content">
              <h4>Hotel Accomodation</h4>
              <p class="mar-0">Enjoy top hotel deals worldwide with comfortable stays, best prices, and easy booking-tailored to your travel needs</p>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-sm-6">
          <div class="why-us-item why-us-item1 text-center">
            <div class="why-us-icon">
              <i class="flaticon-location-pin"></i>
            </div>
            <div class="why-us-content">
              <h4>Tour Peckages</h4>
              <p class="mar-0">Discover exciting tour packages with unbeatable prices, curated experiences, and all-in-one travel convenience</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- why us ends -->

<!-- why us about starts -->
<section class="why-us pad-top-80 bg-grey" id="why-us">
  <div class="container">
    <div class="why-us-about">
      <div class="row display-flex">
        <div class="col-lg-6">
          <div class="why-about-inner">
            <h4>Amazing Places To Enjony Your Travel</h4>
            <h2>We Provide <span>Best Services</span></h2>
            <p class="mar-bottom-25">
              We offer hassle-free air ticket booking for domestic and international flights at the best available fares. Our team ensures quick confirmations, flexible options, and 24/7 support for a smooth travel experience
            </p>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="row">
            <div class="col-lg-6 col-sm-6">
              <div class="why-about-image">
                <img src="{{asset('images_gofly/list3.jpg')}}" alt="about" />
              </div>
            </div>
            <div class="col-lg-6 col-sm-6">
              <div class="why-about-image">
                <img src="{{asset('images_gofly/list1.jpg')}}" alt="about" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- why us about ends -->

<!-- top destination starts -->
<section class="top-destinations top-desti2">
  <div class="container">
    <div class="section-title">
      <h2>Top <span>Destinations</span></h2>
      <p>
        Discover the best holiday packages that fit your budget for your
        favourite destinations.
      </p>
    </div>
    <div class="content">
      <div class="row">
        <div class="col-lg-4 col-md-6 mar-bottom-30">
          <div class="td-item td-item">
            <div class="td-image">
              <img src="{{asset('images_gofly/destination5.jpg')}}" alt="image" />
            </div>
            <!-- <p class="price white">From <span>$350.00</span></p> -->
            <div class="td-content">
              <div class="rating mar-bottom-15">
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
              </div>
              <h3><i class="fa fa-map-marker-alt"></i> Mahabaleshwar</h3>
              <p class="white destination-details-div">Mahabaleshwar, which is also known as the \"Queen of Hill Stations,\" is a lovely place located in Maharashtra. It's high up in the Western Ghats, surrounded by lots of green trees and pretty views. When you go there, you'll feel the cool breeze and see lots of beautiful sights. There are mountains, valleys, and waterfalls all around. It's like stepping into a fairy tale! Here you can visit different spots to see amazing views, like Wilson Point for the sunrise or Kate's Point for the sunset. Mahabaleshwar also has old temples and buildings that tell stories from the past. If you like outdoor activities, there's plenty to do. You can go trekking in the forests, boating on lakes, or horse riding on scenic trails. And if you're lucky, you might spot some interesting birds or animals too. In short, Mahabaleshwar is a wonderful place to visit for anyone who loves nature, history, and adventure.</p>
              <button class="biz-btn book-now-btn" data-toggle="modal" data-target="#bookingModal" data-plan-id="1" data-plan-name="Mahabaleshwar">Book Now</button>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 mar-bottom-30">
          <div class="td-item">
            <div class="td-image">
              <img src="{{asset('images_gofly/destination4.jpg')}}" alt="image" />
            </div>
            <!-- <p class="price white">From <span>$350.00</span></p> -->
            <div class="td-content">
              <div class="rating mar-bottom-15">
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
              </div>
              <h3><i class="fa fa-map-marker-alt"></i> Rishikesh</h3>
              <p class="white destination-details-div">Rishikesh is a small city in India, up in the mountains near the Ganges River. People love it here because it's all about yoga and finding peace. You can learn yoga and meditation from experts who've been practicing for ages. Additionally, if you're into excitement, Rishikesh has it too! You can go rafting in the river, swing from tall bridges, and hike through the hills. The city is old and has temples and big bridges that recount stories from long ago. Every evening, there's a special prayer ceremony by the river. It's like a great show with music, lights, and prayers floating in the air. In short, Rishikesh is the perfect place to relax and forget about all your worries</p>
              <button class="biz-btn book-now-btn" data-toggle="modal" data-target="#bookingModal" data-plan-id="2" data-plan-name="Rishikesh">Book Now</button>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 mar-bottom-30">
          <div class="td-item">
            <div class="td-image">
              <img src="{{asset('images_gofly/destination3.jpg')}}" alt="image" />
            </div>
            <!-- <p class="price white">From <span>$350.00</span></p> -->
            <div class="td-content">
              <div class="rating mar-bottom-15">
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
              </div>
              <h3><i class="fa fa-map-marker-alt"></i> Lonavala</h3>
              <p class="white destination-details-div">Lonavala is located in Maharashtra, a beautiful place surrounded by hills and greenery, far away from the noise and hustle of the city. When you visit Lonavala, you'll feel like you've entered a beautiful world of lush forests, waterfalls, and fresh air. It's the perfect place to relax and unwind, away from the stresses of everyday life. One of the best things about Lonavala is its stunning natural beauty. Everywhere you look, there are beautiful sights that will take your breath away. From the majestic peaks of the Sahyadri mountains to the tranquil waters of the lakes and streams, every view is exquisite. Lonavala is also famous for its delicious food. You can feast on local delicacies like chikki and vada pav, or enjoy a meal at one of the many restaurants serving authentic Maharashtrian cuisine. For those who love adventure, Lonavala has plenty to offer. You can go trekking through the hills, rappelling down waterfalls, or even paragliding over the picturesque landscape. It's like an outdoor playground just waiting to be explored! And if you're in the mood for some relaxation, there are plenty of options to choose from. You can spend a peaceful afternoon by the lakeside, or simply sit back and enjoy the stunning views with a hot cup of tea in hand. In short, it's the perfect destination for a memorable vacation.</p>
              <button class="biz-btn book-now-btn" data-toggle="modal" data-target="#bookingModal" data-plan-id="3" data-plan-name="Lonavala">Book Now</button>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="td-item td-item">
            <div class="td-image">
              <img src="{{asset('images_gofly/destination6.jpg')}}" alt="image" />
            </div>
            <!-- <p class="price white">From <span>$350.00</span></p> -->
            <div class="td-content">
              <div class="rating mar-bottom-15">
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
              </div>
              <h3><i class="fa fa-map-marker-alt"></i> Darjeeling</h3>
              <p class="white destination-details-div">Darjeeling is a city located in the Indian state of West Bengal, nestled in the foothills of the Eastern Himalayas. The weather here is pleasant, not too hot or too cold. Everywhere you look, you can see the beautiful mountains and tea gardens, making it a perfect place for nature lovers. Summer is a great time to visit Darjeeling because you can enjoy adventures like trekking and rafting. You can also learn about tea and try different varieties. Darjeeling is a melting pot of different cultures, so you can experience new things. And, not to forget, the famous toy train ride that provides a fun way to see the sights. In conclusion, Darjeeling in the summer is a cool and exciting place to visit.</p>
              <button class="biz-btn book-now-btn" data-toggle="modal" data-target="#bookingModal" data-plan-id="4" data-plan-name="Darjeeling">Book Now</button>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="td-item">
            <div class="td-image">
              <img src="{{asset('images_gofly/destination7.jpg')}}" alt="image" />
            </div>
            <!-- <p class="price white">From <span>$350.00</span></p> -->
            <div class="td-content">
              <div class="rating mar-bottom-15">
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
              </div>
              <h3><i class="fa fa-map-marker-alt"></i> Ladakh</h3>
              <p class="white destination-details-div">Why visit Ladakh in the summer? Well, Ladakh is like finding a cozy spot under a shady tree on the hottest day of the year - pure bliss, right? Not only is the weather pleasantly cool, but the landscapes are like something out of a dream, with beautiful mountains and clear blue skies. One of the highlights of visiting Ladakh in the summer is the Hemis Festival, which takes place in June or July. This colourful festival celebrates the birth of Guru Padmasambhava, the founder of Tibetan Buddhism, and is a wonderful opportunity to witness traditional dances, music, and other cultural events So, in short, if you're looking for a perfect summer vacation, Ladakh is the perfect destination.</p>
              <button class="biz-btn book-now-btn" data-toggle="modal" data-target="#bookingModal" data-plan-id="5" data-plan-name="Ladakh">Book Now</button>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="td-item">
            <div class="td-image">
              <img src="{{asset('images_gofly/destination8.jpg')}}" alt="image" />
            </div>
            <!-- <p class="price white">From <span>$350.00</span></p> -->
            <div class="td-content">
              <div class="rating mar-bottom-15">
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
              </div>
              <h3><i class="fa fa-map-marker-alt"></i> Nainital</h3>
              <p class="white destination-details-div">Nainital is a beautiful town located in Uttarakhand. It offers a cool retreat from the intense heat of other places. The weather here is delightful with gentle breezes and mild temperatures, making outdoor activities enjoyable and refreshing. You can spend your days boating on the lake, exploring the lush forests, or admiring the beautiful gardens. If you crave some adventure, there are plenty of trekking trails that offer unique scenic beauty. Nainital has its own charm with its quaint hillside cottages and bustling markets filled with local crafts. So, if you're looking for a summer escape that's both refreshing and cooling, Nainital is the perfect destination to chill out.</p>
              <button class="biz-btn book-now-btn" data-toggle="modal" data-target="#bookingModal" data-plan-id="6" data-plan-name="Nainital">Book Now</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- top destination ends -->

<!-- Trending Starts -->
<section class="travel-deals pad-top-0 mt-5 mb-5">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-sm-12 ">
        <!-- section title -->
        <div class="section_heading">
          <h2 class="section_title text-center">
            <span> Frequently Asked Questions (FAQ)</span>
          </h2>
        </div>

        <div class="accrodion-grp faq-accrodion" data-grp-name="faq-accrodion">
          <div class="accrodion active">
            <div class="accrodion-title">
              <h5> Do you offer customized tour packages?</h5>
            </div>
            <div class="accrodion-content" style="display: block">
              <div class="inner ps-4">
                <p>
                  Absolutely! Share your preferences, and we’ll create a package just for you.
                </p>
              </div>
              <!-- /.inner -->
            </div>
          </div>
          <div class="accrodion">
            <div class="accrodion-title">
              <h5>What if I need to cancel or reschedule my trip?</h5>
            </div>
            <div class="accrodion-content" style="display: none">
              <div class="inner ps-4">
                <p>
                  We offer flexible options depending on the airline/hotel policy and provide full support during the
                  process.
                </p>
              </div>
              <!-- /.inner -->
            </div>
          </div>
          <div class="accrodion">
            <div class="accrodion-title">
              <h5>Is customer support available after booking?</h5>
            </div>
            <div class="accrodion-content" style="display: none">
              <div class="inner ps-4">
                <p>
                  Yes, our team is available 24/7 to assist you before, during, and after your trip.
                </p>
              </div>
              <!-- /.inner -->
            </div>
          </div>
          <div class="accrodion">
            <div class="accrodion-title">
              <h5>How do I make changes to my booking?</h5>
            </div>
            <div class="accrodion-content" style="display: none">
              <div class="inner ps-4">
                <p>
                  To make changes to your booking, simply contact our customer support team and we will be happy to assist you.
                </p>
              </div>
              <!-- /.inner -->
            </div>
          </div>
          <div class="accrodion">
            <div class="accrodion-title">
              <h5> Do you help with visa or travel documentation?</h5>
            </div>
            <div class="accrodion-content" style="display: none">
              <div class="inner ps-4">
                <p>
                  Yes, we provide visa guidance and can assist with necessary travel documents.
                </p>
              </div>
              <!-- /.inner -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Trending Ends -->



<!-- Reviews starts-->
<section class="home-testimonial bg-grey">
  <div class="container">
    <div class="section-title">
      <h2>Our Happy Customers</h2>
      <p>
        Find your best way to reach your destiny
      </p>
    </div>
    <!-- rest of the code -->
  </div>
</section>

<!-- End Reviews -->

<!-- Top Featured -->
<section class="travelcounter counter2" style="background: url('{{ asset('images/a.jpg') }}') no-repeat center center; 
           background-size: cover;">
  <div class="container">
    <div class="row display-flex">
      <div class="col-lg-5">
        <div class="section-title title-full width100">
          <h2 class="white">Our Amazing Rating for our customer</h2>
        </div>
      </div>
      <div class="col-lg-7">
        <div class="row service-gg">

          <div class="col-lg-6 col-sm-6">
            <div class="counter-item display-flex">
              <div class="counter-icon">
                <i class="fa fa-walking mar-0" aria-hidden="true"></i>
              </div>
              <div class="counter-content text-left mar-left-30">
                <h3 class="showroom " id="counter-by-usman">10 </h3>
                <p class="mar-0">Traveling Experience</p>
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-sm-6">
            <div class="counter-item display-flex">
              <div class="counter-icon">
                <i class="fa fa-smile mar-0" aria-hidden="true"></i>
              </div>
              <div class="counter-content text-left mar-left-30">
                <h3 class="lisence" id="counter-by-usman-license">100</h3>
                <p class="mar-0">Happy Customers</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- End Top Featured -->

<!-- contact starts -->
<section class="contact-main">
  <div class="container">
    <div class="contact-info mar-bottom-30">
      <div class="row">
        <div class="col-lg-4 col-md-12 col-12">
          <div class="info-item">
            <div class="info-icon">
              <i class="fa fa-map-marker-alt"></i>
            </div>
            <div class="info-content">
              <p>302, Venus Avan Business Center, Bhat
                Circle, Ahmedabad, Gujarat, India Pin:
                382428.
              </p>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12">
          <div class="info-item">
            <div class="info-icon">
              <i class="fa fa-phone"></i>
            </div>
            <div class="info-content">
              <p>+91 6359259244/22</p>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12">
          <div class="info-item">
            <div class="info-icon">
              <i class="fa fa-envelope"></i>
            </div>
            <div class="info-content">
              <p>contact@goflyhabibi</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="contact-map">
      <div class="row">
        <div class="col-lg-6">
          <!-- <div id="map" style="height: 535px; width: 100%"></div> -->
          <img src="{{asset('images_gofly/slider3.jpg')}}" height="100%" alt="">
        </div>
        <div class="col-lg-6">
          <div id="contact-form1" class="contact-form">
            <h3>Keep in Touch</h3>
            <div id="contactform-error-msg"></div>

            <form method="post" action="#" name="contactform" id="contactform">
              <div class="form-group mb-3">
                <input type="text" name="first_name" class="form-control" id="fname"
                  placeholder="First Name" />
              </div>
              <div class="form-group mb-3">
                <input type="text" name="last_name" class="form-control" id="lname"
                  placeholder="Last Name" />
              </div>
              <div class="form-group mb-3">
                <input type="email" name="email" class="form-control" id="email"
                  placeholder="Email" />
              </div>
              <div class="form-group mb-3">
                <input type="text" name="phone" class="form-control" id="phnumber"
                  placeholder="Phone" />
              </div>
              <div class="textarea">
                <textarea name="comments" placeholder="Enter a message"></textarea>
              </div>
              <div class="comment-btn text-right mar-top-15">
                <input type="submit" class="biz-btn" id="submit" value="Send Message" />
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

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
  function removeSegmentRow(button) {
    const segment = button.closest('.city-segment');
    const allSegments = document.querySelectorAll('.city-segment');
    if (allSegments.length > 1) {
      segment.remove();
    } else {
      alert("At least one segment is required.");
    }
  }
</script>
<script>
  function addSegmentRow() {
    const form = document.getElementById('searchFormMulticityUnique');
    const segments = form.querySelectorAll('.city-segment');
    const lastSegment = segments[segments.length - 1];
    const newSegment = lastSegment.cloneNode(true);

    newSegment.classList.add('city-segment');

    // New segment index (starts from 2 for autofill purposes)
    const newIndex = segments.length + 1;

    // Clear input values
    newSegment.querySelectorAll('input[type="text"], input[type="hidden"]').forEach(input => {
      input.value = '';
    });

    // -------- Flying From --------
    const newFromHidden = newSegment.querySelector('input[name^="from_where_multicity_unique"]');
    const newFromText = newSegment.querySelector('input[name^="from_where_text_multicity"]');
    const fromHiddenId = `from_where_multicity_unique${newIndex}`;

    if (newFromHidden) {
      newFromHidden.id = fromHiddenId;
      newFromHidden.name = `from_where_multicity_unique[${newIndex}]`;
    }

    if (newFromText) {
      newFromText.setAttribute('data-hidden-id', fromHiddenId);
      newFromText.setAttribute('data-name-index', newIndex);
      newFromText.name = `from_where_text_multicity[${newIndex}]`;
    }

    // -------- Flying To --------
    const newToHidden = newSegment.querySelector('input[name^="to_where_multicity_unique"]');
    const newToText = newSegment.querySelector('input[name^="to_where_text_multicity"]');
    const toHiddenId = `to_where_multicity_unique${newIndex}`;

    if (newToHidden) {
      newToHidden.id = toHiddenId;
      newToHidden.name = `to_where_multicity_unique[${newIndex}]`;
    }

    if (newToText) {
      newToText.setAttribute('data-hidden-id', toHiddenId);
      newToText.setAttribute('data-name-index', newIndex);
      newToText.name = `to_where_text_multicity[${newIndex}]`;
    }

    // -------- Depart Date --------
    const dateInput = newSegment.querySelector('input[name^="departure_date_multicity_unique"]');
    let newDateClass = '';

    if (dateInput) {
      dateInput.className = dateInput.className
        .split(' ')
        .filter(cls => !cls.startsWith('departure_date_multicity_unique'))
        .join(' ')
        .trim();

      newDateClass = `departure_date_multicity_unique${newIndex}`;
      dateInput.classList.add(newDateClass);
      dateInput.setAttribute('data-name-index', newIndex);
      dateInput.name = `departure_date_multicity_unique[${newIndex}]`;
    }


    // -------- Autofill "From" of new row (starting from segment 2 only) --------
    const prevToText = lastSegment.querySelector('input[name^="to_where_text_multicity"]');
    const prevToHidden = lastSegment.querySelector('input[name^="to_where_multicity_unique"]');

    // Only autofill FROM field if newIndex >= 2
    if (newIndex > 1) {
      if (newFromText && prevToText) newFromText.value = prevToText.value;
      if (newFromHidden && prevToHidden) newFromHidden.value = prevToHidden.value;
    }

    // -------- Remove conflicting IDs --------
    newSegment.querySelectorAll('[id]').forEach(el => {
      const id = el.id;
      if (
        !id.includes('from_where_multicity_unique') &&
        !id.includes('to_where_multicity_unique')
      ) {
        el.removeAttribute('id');
      }
    });

    // -------- Clear dropdowns --------
    newSegment.querySelectorAll('.optionsList').forEach(list => {
      list.innerHTML = '';
      list.style.display = 'none';
    });

    // -------- Remove old buttons --------
    newSegment.querySelectorAll('.add-segment-btn, .remove-segment-btn').forEach(btn => btn.remove());
    const actionCol = newSegment.querySelector('.segment-action');
    if (actionCol) actionCol.innerHTML = '';

    // -------- Insert new segment --------
    lastSegment.after(newSegment);

    // -------- Real-time autofill: only if newIndex > 1 --------
    if (newIndex > 1 && newToText) {
      newToText.addEventListener('input', function() {
        const nextIndex = newIndex + 1;
        const nextFromInput = document.querySelector(`input[name="from_where_text_multicity[${nextIndex}]"]`);
        const nextFromHidden = document.querySelector(`input[name="from_where_multicity_unique[${nextIndex}]"]`);
        const currentToHidden = document.querySelector(`input[name="to_where_multicity_unique[${newIndex}]"]`);

        if (nextFromInput) nextFromInput.value = this.value;
        if (nextFromHidden && currentToHidden) nextFromHidden.value = currentToHidden.value;
      });
    }

    // -------- Reinit logic --------
    recalculateSegmentButtons();
    if (newDateClass) {
      initDatePickerByClass(newDateClass);
    }

  }


  function removeSegmentRow(button) {
    const form = document.getElementById('searchFormMulticityUnique');
    const segments = form.querySelectorAll('.city-segment');

    if (segments.length <= 1) {
      alert("At least one segment must remain.");
      return;
    }

    const segment = button.closest('.city-segment');
    segment.remove();

    recalculateSegmentButtons();
  }


  function recalculateSegmentButtons() {
    const segments = document.querySelectorAll('.city-segment');

    segments.forEach((segment, idx) => {
      const actionCol = segment.querySelector('.segment-action');
      if (actionCol) {
        actionCol.innerHTML = '';

        if (idx === segments.length - 1) {
          actionCol.innerHTML = `
          <button type="button" class="biz-btn add-segment-btn" style="margin-top: 35px; margin-right:20px; width:130px;" onclick="addSegmentRow()">
            +Add More
          </button>`;

          if (segments.length > 1) {
            actionCol.innerHTML += `
            <button type="button" class="biz-btn btn-sm remove-segment-btn" title="Remove" style="margin-top: 40px; width:40px; height:40px; display: flex; align-items: center; justify-content: center;" onclick="removeSegmentRow(this)">
              <i class="fas fa-times"></i>
            </button>`;
          }
        }
      }
    });
  }

  function initDatePickerByClass(className) {
    $(`.${className}`).dateRangePicker({
      autoClose: true,
      singleDate: true,
      showShortcuts: false,
      singleMonth: true,
      showTopbar: false,
      extraClass: 'reserved-form',
      beforeShowDay: function(t) {
        const today = new Date();
        const target = new Date(t);

        today.setHours(0, 0, 0, 0);
        target.setHours(0, 0, 0, 0);

        return target < today ? [false, '', 'Unavailable'] : [true, '', ''];
      }
    });
  }
</script>

<!-- Auto-show Modal on Page Load -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var welcomeModal = new bootstrap.Modal(document.getElementById('welcomeModal'), {
      keyboard: false
    });
    welcomeModal.show();
  });
</script>
<script>
  function toggleDropdown(input) {
    const wrapper = input.closest('.niceSelectWrapper');
    const dropdown = wrapper.querySelector('.optionsList');

    // Close all other dropdowns
    document.querySelectorAll('.optionsList').forEach(d => {
      if (d !== dropdown) d.style.display = 'none';
    });

    dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' : 'none';
  }

  function filterOptions(input) {
    const wrapper = input.closest('.niceSelectWrapper');
    const optionsList = wrapper.querySelector('.optionsList');
    optionsList.innerHTML = ''; // Clear old results

    fetch(`/airports/search?query=${encodeURIComponent(input.value)}`)
      .then(response => response.json())
      .then(data => {
        if (data.length === 0) {
          optionsList.innerHTML = '<div class="option">No results found</div>';
          return;
        }

        data.slice(0, 5).forEach(airport => {
          // We only search by address now
          if (!airport.address || !airport.address.toLowerCase().includes(input.value.toLowerCase())) return;

          const highlightedAddress = airport.address.replace(
            new RegExp(`(${input.value})`, 'ig'),
            '<span class="highlight">$1</span>'
          );

          const optionDiv = document.createElement('div');
          optionDiv.className = 'option';
          optionDiv.innerHTML = `
                    <div>${highlightedAddress}</div>
                    <div style="font-size: 12px; color: gray;">${airport.name}</div>
                `;
          optionDiv.setAttribute('data-code', airport.code);

          optionDiv.onclick = function() {
            selectOptionFromAjax(this, input, airport.address, airport.code);
          };

          optionsList.appendChild(optionDiv);
        });
      })
      .catch(() => {
        optionsList.innerHTML = '<div class="option">Error loading results</div>';
      });
  }


  document.addEventListener('click', function(e) {
    document.querySelectorAll('.niceSelectWrapper').forEach(function(wrapper) {
      if (!wrapper.contains(e.target)) {
        wrapper.querySelector('.optionsList').style.display = 'none';
      }
    });
  });

  function selectOptionFromAjax(optionDiv, input, name, code) {
    input.value = name;
    const hiddenInputId = input.getAttribute('data-hidden-id');
    if (hiddenInputId) {
      const hiddenInput = document.getElementById(hiddenInputId);
      if (hiddenInput) {
        hiddenInput.value = code;
      }
    }
    const wrapper = input.closest('.niceSelectWrapper');
    const optionsList = wrapper.querySelector('.optionsList');
    optionsList.style.display = 'none';
    // ✅ Auto-fill "From" field if this is the "To" field
    if (input.name === 'to_where_text_multicity[]') {
      setToAndFromValues(input);
    }
  }
</script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const dateInput = document.getElementById("date-range0");

    // Get today's date in yyyy-mm-dd format
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, "0");
    const dd = String(today.getDate()).padStart(2, "0");
    const formattedToday = `${yyyy}-${mm}-${dd}`;

    // Set min and max
    dateInput.min = formattedToday;


    // Clear value if it's before today
    if (dateInput.value && dateInput.value < formattedToday) {
      dateInput.value = ""; // clear invalid previous value
    }
  });
</script>
<!-- jQuery required -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Counter buttons
    document.querySelectorAll(".traveler-btn").forEach(function(btn) {
      btn.addEventListener("click", function() {
        const type = this.getAttribute("data-type");
        const action = this.getAttribute("data-action");
        const countEl = document.getElementById(`${type}Count`);
        let count = parseInt(countEl.innerText) || 0;

        if (action === "minus") {
          if (type === "adult" && count > 1) count--;
          else if ((type === "child" || type === "infant") && count > 0) count--;
        } else if (action === "plus") {
          count++;
        }

        countEl.innerText = count;
        updateTravelerSummary();
      });
    });

    // Update summary on travel class change
    document.getElementById("travelClass").addEventListener("change", updateTravelerSummary);

    // Initial render
    updateTravelerSummary();
  });

  function updateTravelerSummary() {
    const adults = parseInt(document.getElementById("adultCount").innerText) || 0;
    const children = parseInt(document.getElementById("childCount").innerText) || 0;
    const infants = parseInt(document.getElementById("infantCount").innerText) || 0;
    const travelClass = document.getElementById("travelClass").value;

    const total = adults + children + infants;

    const summary = `${total} Traveler${total > 1 ? 's' : ''} - ${travelClass}`;
    document.getElementById("travelerSummary").innerText = summary;
  }
</script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Counter buttons specific to oneway
    document.querySelectorAll(".traveler-btn[data-context='oneway']").forEach(function(btn) {
      btn.addEventListener("click", function() {
        const type = this.getAttribute("data-type");
        const action = this.getAttribute("data-action");
        const countEl = document.getElementById(`${type}CountOneway`);
        let count = parseInt(countEl.innerText) || 0;

        if (action === "minus") {
          if (type === "adult" && count > 1) count--;
          else if ((type === "child" || type === "infant") && count > 0) count--;
        } else if (action === "plus") {
          count++;
        }

        countEl.innerText = count;
        updateTravelerSummaryOneway();
      });
    });

    // Class dropdown listener
    const classSelect = document.getElementById("travelClassOneway");
    if (classSelect) {
      classSelect.addEventListener("change", updateTravelerSummaryOneway);
    }

    // Init summary once page loads
    updateTravelerSummaryOneway();
  });

  function updateTravelerSummaryOneway() {
    const adults = parseInt(document.getElementById("adultCountOneway").innerText) || 0;
    const children = parseInt(document.getElementById("childCountOneway").innerText) || 0;
    const infants = parseInt(document.getElementById("infantCountOneway").innerText) || 0;
    const travelClass = document.getElementById("travelClassOneway").value;

    const total = adults + children + infants;
    const summary = `${total} Traveler${total > 1 ? 's' : ''} - ${travelClass}`;

    // Update visible text
    document.getElementById("travelerSummaryOneway").innerText = summary;

    // Update hidden summary input
    const hiddenInput = document.getElementById("travelSummaryInputOneway");
    if (hiddenInput) hiddenInput.value = summary;

    // ✅ Update hidden count inputs
    document.getElementById("adultCountInputOneway").value = adults;
    document.getElementById("childCountInputOneway").value = children;
    document.getElementById("infantCountInputOneway").value = infants;
  }
</script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Counter buttons specific to multicity
    document.querySelectorAll(".traveler-btn[data-context='multicity_unique']").forEach(function(btn) {
      btn.addEventListener("click", function() {
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
  document.getElementById("searchFormOneway").addEventListener("submit", function(e) {
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

    this.action = `/gofly/search?${queryString}`; // ✅ Use your route here

    this.submit();
  });
</script>
<script>
  document.getElementById("searchFormRoundtrip").addEventListener("submit", function(e) {
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

    this.action = `/gofly/search?${queryString}`; // ✅ Use your route here

    this.submit();
  });
</script>
<script>
  document.getElementById("searchFormRoundtrip").addEventListener("submit", function(e) {
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

    this.action = `/gofly/search/roundtrip?${queryString}`;
    this.submit();
  });
</script>
<script>
  document.getElementById("searchFormMulticityUnique").addEventListener("submit", function(e) {
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
    form.action = `/gofly/search/multicity?${queryParams.toString()}`;
    form.submit();
  });
</script>


@endsection