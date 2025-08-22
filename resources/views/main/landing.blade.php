@include('layout_new.layout')
    <!-- Bootstrap core CSS -->
    <link href="{{asset('css_gofly/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!--Default CSS-->
    <link href="{{asset('css_gofly/default.css')}}" rel="stylesheet" type="text/css" />
    <!--Custom CSS-->
    <link href="{{asset('css_gofly/style.css')}}" rel="stylesheet" type="text/css" />
    <!--Color Switcher CSS-->
    <link rel="stylesheet" href="{{asset('css_gofly/color/color-default.css')}}" />
    <!--Plugin CSS-->
    <link href="{{asset('css_gofly/plugin.css')}}" rel="stylesheet" type="text/css" />
    <!--Flaticons CSS-->
    <link href="{{asset('fonts_gofly/flaticon.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/longbill/jquery-date-range-picker@latest/dist/daterangepicker.min.css" />
    <script src="https://cdn.jsdelivr.net/gh/longbill/jquery-date-range-picker@latest/dist/jquery.daterangepicker.min.js"></script>
    <script src="js/jquery-3.3.1.min.js"></script>
    <!--Font Awesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" />

    <script>
    $(function() {
        const today = new Date();
        const formattedToday = today.toISOString().split('T')[0]; // YYYY-MM-DD
        $("#hotel-date-range").dateRangePicker({
            autoClose: true,
            showShortcuts: false,
            singleMonth: false,
            showTopbar: false,
            minDate: formattedToday,
            separator: " - ",
            format: "YYYY-MM-DD",
            extraClass: "reserved-form"
        });
    });

    $('#flight-date-range').bind('datepicker-change', function(event, obj) {
        if (obj.date1 && obj.date2) {
            $('#flight-date-range').data('dateRangePicker').close();
        }
    });

    $(function () {
    const today = new Date();
    const formattedToday = today.toISOString().split('T')[0]; // YYYY-MM-DD

    $("#flight-date-range").dateRangePicker({
        autoClose: false,
        showShortcuts: false,
        singleMonth: false,
        showTopbar: false,
        singleDate: true,
        format: "YYYY-MM-DD",
        minDate: new Date().toISOString().split('T')[0],
        separator: " - ",
        extraClass: "reserved-form",
        onSelect: function() {
            // Close the picker after selection
            $(this).data('dateRangePicker').close();
        }
    });
});


    document.querySelector("#hotel-search-form").addEventListener("submit", function(e) {
        e.preventDefault(); // Prevent default form submission

        // Get the values from the form
        const form = document.querySelector("#hotel-search-form");
        const formData = new FormData(form);
        const destination = document.getElementById("location-search-input").value; // Get the destination value
        const locationId = formData.get("location_id");
        const checkIn = formData.get("checkIn");
        const checkOut = formData.get("checkOut");
        const rooms = formData.get("rooms");
        const adults = formData.get("adults");
        const children = formData.get("children");

        // Create a new URLSearchParams object
        const searchParams = new URLSearchParams();

        // Add the data to the URL search params
        if (locationId) searchParams.append("location_id", locationId);
        if (destination) searchParams.append("destination", destination); // Add destination as a parameter
        if (checkIn) searchParams.append("start[]", checkIn);
        if (checkOut) searchParams.append("end[]", checkOut);
        if (rooms) searchParams.append("room", rooms);
        if (adults) searchParams.append("adults", adults);
        if (children) searchParams.append("children", children);

        // Create the full URL (use current URL and append the params)
        const currentUrl = new URL(window.location.href);
        currentUrl.search = searchParams.toString(); // Append the new query params

        // Redirect to the new URL with query params
        window.location.href = currentUrl.toString();
    });

    function abc(e) {
        const form = document.querySelector('form');
        const formData = new FormData(form);

        // Get full date range (e.g., "2025-04-19 - 2025-04-20")
        const dateRange = formData.get("date");

        if (dateRange && dateRange.includes(" - ")) {
            const [startRaw, endRaw] = dateRange.split(" - ");
            const startDate = startRaw.trim().replace(/-/g, "/");
            const endDate = endRaw.trim().replace(/-/g, "/");

            // Add the two custom params
            formData.append("start[]", startDate);
            formData.append("end[]", endDate);
        }

        // Get the destination from the input field
        const destination = document.getElementById("location-search-input").value;

        if (destination) {
            formData.append("destination", destination); // Append destination
        }

        // Build the URL with form data
        const params = new URLSearchParams(formData).toString();
        const baseUrl = "/search-hotels";

        // Redirect to the new URL
        window.location.href = `${baseUrl}?${params}`;
    }

    function hotelSubmit(e) {
        const form = document.querySelector('#flight-search-form');
        const formData = new FormData(form);
        const dateRange = formData.get("date");

        if (dateRange) {
            const startDate = dateRange.trim().replace(/-/g, "/");

            formData.append("start[]", startDate);
        }
        formData.append("travel_type", "One Way");

        const params = new URLSearchParams(formData).toString();
        const baseUrl = "/flight";

        window.location.href = `${baseUrl}?${params}`;
    }

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

                optionDiv.onclick = function () {
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
    }

    document.addEventListener('click', function(event) {
        const travelerBoxes = document.querySelectorAll('.input-box');
        travelerBoxes.forEach(function(box) {
            if (!box.contains(event.target)) {
                const dropdown = box.querySelector('.traveler-dropdown');
                if (dropdown && dropdown.style.display === 'block') {
                    dropdown.style.maxHeight = 0;
                    dropdown.style.opacity = 0;
                    setTimeout(() => dropdown.style.display = 'none', 300);
                }
            }
        });
    });
</script>

    <!-- CSS to make radio buttons circle -->
    <style>
        .highlight {
            background-color: yellow;
            font-weight: bold;
            display: inline;
            /* Ensures it stays on the same line */
        }

        .custom-location-select .optionsList {
            display: none;
            position: absolute;
            background: #fff;
            border: 1px solid #ccc;
            width: 100%;
            z-index: 1000;
        }

        .custom-location-select {
            position: relative;
        }

        .custom-radio {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 1px solid #999;
            background-color: #fff;
            appearance: none;
            -webkit-appearance: none;
            outline: none;
            cursor: pointer;
            position: relative;
        }

        .custom-radio:checked {
            background-color: #dc3545;
            /* Red when selected */
            border: 1px solid #dc3545;
        }

        .form-check-label {
            margin-left: 8px;
            font-size: 14px;
            cursor: pointer;
        }

        .filter-box .form-check-input[type=radio] {
            padding-left: 0px;
        }
    </style>

    <!-- CSS to make radio buttons circle -->
    <style>
        .traveler-dropdown {
            display: none;
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.4s ease, opacity 0.4s ease;
            opacity: 0;
        }

        .traveler-dropdown.show {
            display: block;
            max-height: 500px;
            /* enough height to show content */
            opacity: 1;
        }

        .traveler-dropdown.hide {
            max-height: 0;
            opacity: 0;
            display: block;
        }

        .traveler-dropdown {
            display: none;
            overflow: hidden;
            max-height: 0;
            /* Start with a max height of 0 */
            opacity: 0;
            /* Start with zero opacity */
            transition: max-height 0.4s ease, opacity 0.4s ease;
        }

        .traveler-dropdown.show {
            max-height: 500px;
            /* Adjust this based on your content */
            opacity: 1;
            display: block;
            /* Make sure it's displayed before transitioning */
        }

        .traveler-dropdown.hide {
            max-height: 0;
            opacity: 0;
        }
    </style>


    <!-- This styling is for input search group -->
    <style>
        .niceSelectWrapper {
            position: relative;
            width: 100%;
        }

        .searchInput {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
        }

        .optionsList {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            border: 1px solid #ccc;
            background: white;
            max-height: 200px;
            overflow-y: auto;
            z-index: 99;
            border-radius: 0 0 5px 5px;
        }

        .option {
            padding: 10px;
            cursor: pointer;
        }

        .option:hover {
            background: #f5f5f5;
        }
    </style>

    <style>
        .nav-tabs .nav-link.active {
            background-color: #ec7b34;
            color: #fff;
            padding: 18px 36px;
            border-radius: 12px 12px 0 0;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link {
            background-color: #fff;
            color: #7b7b7b;
            cursor: pointer;
            border: none;
            padding: 10px 15px 8px;
            border-radius: 8px 8px 0 0;
            transition: all 0.3s ease;
        }
    </style>
    <!-- JavaScript for functionality -->
    <script>
        // function toggleTravelerDropdown() {
        //   const dropdown = document.getElementById('traveler-dropdown');
        //   dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' : 'none';
        // }

        function toggleTravelerDropdown(input) {
            const dropdown = input.closest('.input-box').querySelector('.traveler-dropdown');
            const isVisible = dropdown.style.display === 'block';

            // Toggle dropdown visibility with smooth animation
            if (isVisible) {
                dropdown.style.maxHeight = 0;
                dropdown.style.opacity = 0;
                setTimeout(() => dropdown.style.display = 'none', 300); // Wait for animation to complete
            } else {
                dropdown.style.display = 'block';
                setTimeout(() => {
                    dropdown.style.maxHeight = '500px'; // Max height can be adjusted based on content
                    dropdown.style.opacity = 1;
                }, 10); // Small delay to allow display: block to take effect
            }
        }

        function toggleHotelDropdown(input) {
            const dropdown = input.closest('.input-box').querySelector('.hotel-dropdown');
            const isVisible = dropdown.style.display === 'block';

            // Toggle dropdown visibility with smooth animation
            if (isVisible) {
                dropdown.style.maxHeight = 0;
                dropdown.style.opacity = 0;
                setTimeout(() => dropdown.style.display = 'none', 300); // Wait for animation to complete
            } else {
                dropdown.style.display = 'block';
                setTimeout(() => {
                    dropdown.style.maxHeight = '500px'; // Max height can be adjusted based on content
                    dropdown.style.opacity = 1;
                }, 10); // Small delay to allow display: block to take effect
            }
        }

        function updateCount(type, change) {
            const countElement = document.getElementById(`${type}-count`);
            let count = parseInt(countElement.value);

            count += change;

            type === 'children' ? (count < 0 && (count = 0)) : (count < 1 && (count = 1));
            countElement.value = count;
            updateTravelerSummary();
        }

        function updateTravelerSummary() {
            const adults = parseInt(document.getElementById('adults-count').value);
            const children = parseInt(document.getElementById('children-count').value);
            const infants = parseInt(document.getElementById('infants-count').value);

            const totalTravelers = adults + children;

            const travelerSummaryInput = document.getElementById('flight-summary');
            travelerSummaryInput.value =
                `${totalTravelers} Traveler${totalTravelers > 1 ? 's' : ''} - ${infants} Room${infants > 1 ? 's' : ''}`;
        }


        function updateHotelCount(type, change) {
            const countElement = document.getElementById(`${type}-hotel`);
            let count = parseInt(countElement.value);

            count += change;

            type === 'adults' ? (count < 1 && (count = 1)) : (count < 0 && (count = 0));
            countElement.value = count;
            updateHotelSummary();
        }

        function updateHotelSummary() {
            const adults = parseInt(document.getElementById('adults-hotel').value);
            const children = parseInt(document.getElementById('children-hotel').value);
            const infants = parseInt(document.getElementById('infants-hotel').value);

            const totalTravelers = adults + children + infants;
            const input = document.getElementById('hotel-summary');
            const selectedClassRadio = document.querySelector('input[name="seat_type[class]"]:checked');
            let selectedClass = selectedClassRadio ? selectedClassRadio.labels[0].innerText : 'Economy';

            input.placeholder = `${totalTravelers} Traveler${totalTravelers > 1 ? 's' : ''} - ${selectedClass}`;
        }
        // Close dropdown if clicked outside
        document.addEventListener('click', function(event) {
            const travelerBox = document.querySelector('.input-box');
            const dropdown = document.getElementById('traveler-dropdown');

            if (!travelerBox.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });
    </script>


</head>

<body>
    <!-- Preloader -->
    <div id="preloader">
        <div id="status"></div>
    </div>
    <!-- Preloader Ends -->

    <!-- header starts -->
    @include('layout_new.header')
   
    <section class="banner banner1" style="overflow: hidden; position: relative; height: 100vh;">
        <div class="slide-inner" style="position: relative; width: 100%; height: 100%;">
            <div class="slide-image zoom-effect"
                style="background-image: url('images/slider/slider6.jpg')}}'); background-size: cover; background-position: center; width: 100%; height: 100%;">
            </div>

            <div class="swiper-content swi-content"
                style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; z-index: 2;">
                <div class="banner-form form-style2">
                    <div class="form-content mb-4">
                        <div class="price-navtab text-center">
                            <ul class="nav nav-tabs border-0 ms-4 d-flex align-items-end gap-1">
                                <li class="nav-item m-0">
                                    <a class="nav-link active" data-bs-toggle="tab" data-bs-target="#hotel"><i
                                            class="flaticon-building"></i>
                                        Hotel</a>
                                </li>
                                <li class="nav-item m-0">
                                    <a class="nav-link" data-bs-toggle="tab" data-bs-target="#flight"><i
                                            class="fa fa-plane fa-lg me-1"></i> Flight</a>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content">
                            <div id="hotel" class="tab-pane show active">
                                <form id="hotel-search-form" onsubmit="abc()">
                                    <div class="row filter-box align-items-end">
                                        <div class="col-lg-3 col-sm-12">
                                            <div class="form-group text-start">
                                                <label>Your Destination</label>
                                                <div class="input-box">
                                                    <i class="flaticon-placeholder"></i>
                                                    <div class="custom-location-select">
                                                        <input type="text" id="location-search-input"
                                                            class="form-control" placeholder="Where are you going?"
                                                            onkeyup="filterLocationOptions()">
                                                        <div id="location-options-list" class="optionsList"
                                                            style="max-height: 200px; overflow-y: auto;">
                                                            <!-- <div class="option" data-value="2015" onclick="selectLocationOption(this)">India - Tirurangadi</div>
    <div class="option" data-value="2016" onclick="selectLocationOption(this)">India - Karthikappally</div>
    <div class="option" data-value="2017" onclick="selectLocationOption(this)">India - Pathanapuram</div>
    <div class="option" data-value="2018" onclick="selectLocationOption(this)">India - Iritty</div> -->
                                                        </div>
                                                        <input type="hidden" name="location_id"
                                                            id="selected-location-id">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="form-group text-start">
                                                <label>Reserved Date</label>
                                                <div class="input-box">
                                                    <i class="flaticon-calendar"></i>
                                                    <input id="hotel-date-range" name="date" type="text"
                                                        placeholder="yyyy-mm-dd" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <div class="form-group text-start">
                                                <label>Person</label>

                                                <div class="input-box position-relative"
                                                    onclick="event.stopPropagation()">
                                                    <i class="flaticon-add-user"></i>

                                                    <!-- Traveler Summary Input -->
                                                    <input type="text" id="flight-summary" readonly
                                                        placeholder="1 Traveler - 1 Room" class="form-control"
                                                        onclick="toggleTravelerDropdown(this)" />

                                                    <!-- Traveler Dropdown -->
                                                    <div id="traveler-dropdown"
                                                        class="traveler-dropdown dropdown-menu p-2 px-3 mt-2 end-0"
                                                        style="display: none; max-width: 400px; width: 400px; min-width: 100%;">

                                                        <!-- Adults -->
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-3">
                                                            <div>
                                                                <strong>Adults</strong><br>
                                                                <small class="text-muted">Age 18-64</small>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-light me-2"
                                                                    onclick="updateCount('adults', -1)">−</button>
                                                                <input type="text" name="adults" readonly
                                                                    id="adults-count" value="1"
                                                                    class="border-0 text-center"
                                                                    style="padding: 0px; max-width: 30px">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger ms-2"
                                                                    onclick="updateCount('adults', 1)">+</button>
                                                            </div>
                                                        </div>

                                                        <!-- Children -->
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-3">
                                                            <div>
                                                                <strong>Children</strong><br>
                                                                <small class="text-muted">Age 3–17</small>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-light me-2"
                                                                    onclick="updateCount('children', -1)">−</button>
                                                                <input type="text" id="children-count"
                                                                    name="children" value="0" readonly
                                                                    class="border-0 text-center"
                                                                    style="padding: 0px; max-width: 30px">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger ms-2"
                                                                    onclick="updateCount('children', 1)">+</button>
                                                            </div>
                                                        </div>

                                                        <!-- Infants -->
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-4">
                                                            <div>
                                                                <strong>Room</strong><br>
                                                                <small class="text-muted"></small>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-light me-2"
                                                                    onclick="updateCount('infants', -1)">−</button>
                                                                <input type="text" id="infants-count"
                                                                    name="room" value="1" readonly
                                                                    class="border-0 text-center"
                                                                    style="padding: 0px; max-width: 30px">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger ms-2"
                                                                    onclick="updateCount('infants', 1)">+</button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-sm-12">
                                            <div class="form-group text-start mar-0">
                                                <button type="submit" class="biz-btn"><i class="fa fa-search"></i>
                                                    Find Now</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div id="flight" class="tab-pane">
                                <form id="flight-search-form" onsubmit="hotelSubmit()">
                                    <div class="row filter-box align-items-end">
                                        <div class="col-lg-3 col-sm-12">
                                            <div class="form-group text-start">
                                                <label>Flying From</label>
                                                <div class="input-box">
                                                    <i class="flaticon-placeholder"></i>
                                                    <div class="niceSelectWrapper input-group">
                                                        <input hidden name="from_where[]" id="from_where" />
                                                        <input type="text" class="searchInput"
                                                            placeholder="Where are you going?"
                                                            onclick="toggleDropdown(this)"
                                                            onkeyup="filterOptions(this)" data-hidden-id="from_where">

                                                        <div class="optionsList" style="display: none;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-sm-12">
                                            <div class="form-group text-start">
                                                <label>Flying To</label>
                                                <div class="input-box">
                                                    <i class="flaticon-placeholder"></i>
                                                    <div class="niceSelectWrapper input-group">
                                                        <input hidden name="to_where[]" id="to_where" />
                                                        <input type="text" class="searchInput"
                                                            placeholder="Where are you going?"
                                                            onclick="toggleDropdown(this)"
                                                            onkeyup="filterOptions(this)" data-hidden-id="to_where">
                                                        <div class="optionsList" style="display: none;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="form-group text-start">
                                                <label>Depart Date</label>
                                                <div class="input-box">
                                                    <i class="flaticon-calendar"></i>
                                                    <input id="flight-date-range" name="date" type="text" placeholder="yyyy-mm-dd" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="form-group text-start">
                                                <label>Booking</label>
                                                <!-- Traveler Input Box -->
                                                <div class="input-box position-relative traveler-box"
                                                    onclick="event.stopPropagation()"
                                                    style="display: flex; align-items: center;">
                                                    <i class="flaticon-add-user"
                                                        style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%);"></i>

                                                    <input type="text" readonly placeholder="1 Traveler - Economy"
                                                        class="form-control seat-type-flight" id="hotel-summary"
                                                        onclick="toggleHotelDropdown(this)"
                                                        style="padding-left: 45px;" />

                                                    <div class="dropdown-menu hotel-dropdown p-2 px-3 mt-2 end-0"
                                                        style="display: none; width: 400px; top: 42px;">

                                                        <!-- Adults -->
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-3">
                                                            <div>
                                                                <strong>Adults</strong><br>
                                                                <small class="text-muted">Age 18–64</small>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-light me-2"
                                                                    onclick="updateHotelCount('adults', -1)">−</button>
                                                                <input type="text" name="seat_type[adults]"
                                                                    readonly id="adults-hotel" value="1"
                                                                    class="border-0 text-center"
                                                                    style="padding: 0px; max-width: 30px">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger ms-2"
                                                                    onclick="updateHotelCount('adults', 1)">+</button>
                                                            </div>
                                                        </div>

                                                        <!-- Children -->
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-3">
                                                            <div>
                                                                <strong>Children</strong><br>
                                                                <small class="text-muted">Age 3–17</small>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-light me-2"
                                                                    onclick="updateHotelCount('children', -1)">−</button>
                                                                <input type="text" name="seat_type[children]"
                                                                    readonly id="children-hotel" value="0"
                                                                    class="border-0 text-center"
                                                                    style="padding: 0px; max-width: 30px">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger ms-2"
                                                                    onclick="updateHotelCount('children', 1)">+</button>
                                                            </div>
                                                        </div>

                                                        <!-- Infants -->
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-4">
                                                            <div>
                                                                <strong>Infants</strong><br>
                                                                <small class="text-muted">Age 0–2</small>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-light me-2"
                                                                    onclick="updateHotelCount('infants', -1)">−</button>
                                                                <input type="text" name="seat_type[infants]"
                                                                    readonly id="infants-hotel" value="0"
                                                                    class="border-0 text-center"
                                                                    style="padding: 0px; max-width: 30px">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger ms-2"
                                                                    onclick="updateHotelCount('infants', 1)">+</button>
                                                            </div>
                                                        </div>


                                                        <div class="mb-3">
                                                            <strong class="d-block mb-2">Select Travel Class</strong>
                                                            <div class="row g-2">
                                                                <div class="col-6">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input custom-radio"
                                                                            type="radio" name="seat_type[class]"
                                                                            id="eco" value="eco" checked
                                                                            onchange="updateSeatType('eco')">
                                                                        <label class="form-check-label"
                                                                            for="eco">Economy</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input custom-radio"
                                                                            type="radio" name="seat_type[class]"
                                                                            id="business" value="business"
                                                                            onchange="updateSeatType('business')">
                                                                        <label class="form-check-label"
                                                                            for="business">Business</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input custom-radio"
                                                                            type="radio" name="seat_type[class]"
                                                                            id="premium" value="premium"
                                                                            onchange="updateSeatType('premium')">
                                                                        <label class="form-check-label"
                                                                            for="premium">Premium</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input custom-radio"
                                                                            type="radio" name="seat_type[class]"
                                                                            id="first_class" value="first_class"
                                                                            onchange="updateSeatType('firstclass')">
                                                                        <label class="form-check-label"
                                                                            for="first_class">First Class</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input custom-radio"
                                                                            type="radio" name="seat_type[class]"
                                                                            id="vip" value="vip"
                                                                            onchange="updateSeatType('vip')">
                                                                        <label class="form-check-label"
                                                                            for="vip">Vip</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <!-- Travel Class Radios -->
                                                <script>
                                                    function updateSeatType(val) {
                                                        // Get the latest traveler count from the inputs
                                                        const adults = parseInt(document.getElementById('adults-hotel').value);
                                                        const children = parseInt(document.getElementById('children-hotel').value);
                                                        const infants = parseInt(document.getElementById('infants-hotel').value);
                                                        const totalTravelers = adults + children + infants;

                                                        const input = document.querySelector('.seat-type-flight');
                                                        // Format class label
                                                        let classLabel = val.charAt(0).toUpperCase() + val.slice(1).replace('_', ' ');
                                                        if (val === 'eco') classLabel = 'Economy';
                                                        input.placeholder = `${totalTravelers} Traveler${totalTravelers > 1 ? 's' : ''} - ${classLabel}`;
                                                    }
                                                </script>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-sm-12">
                                            <div class="form-group text-start mar-0">
                                                <button type="submit" class="biz-btn"><i class="fa fa-search"></i>
                                                    Find Now</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <h1>Make you Free to <span>travel</span> with us</h1>
        <p class="mar-bottom-30">Foresee the pain and trouble that are bound to ensue and equal fail in their duty
          through weakness.</p>
        <a href="#" class="biz-btn">Explore More</a>
        <a href="#" class="biz-btn mar-left-10">Contact Us</a> -->
            </div>

            <div class="overlay"
                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); z-index: 1;">
            </div>
        </div>
    </section>

    <!-- form starts -->


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
                                <p class="mar-0">Grab the best deals  on domestic and international flights with our quick and easy air tricketing services</p>
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
                                <h3><i class="fa fa-map-marker-alt"></i>  Mahabaleshwar</h3>
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
                                <h3><i class="fa fa-map-marker-alt"></i>  Rishikesh</h3>
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
                                <h3><i class="fa fa-map-marker-alt"></i>  Lonavala</h3>
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
                                <h3><i class="fa fa-map-marker-alt"></i>  Darjeeling</h3>
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
                                <h3><i class="fa fa-map-marker-alt"></i>  Ladakh</h3>
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
                                <h3><i class="fa fa-map-marker-alt"></i>  Nainital</h3>
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
                <h2>Our Happy Costumers</h2>
                <p>
                Find your best way to reach your destiny
                </p>
            </div>
            <div class="row testimonial-slider">
                <div class="ts-item col-lg-4 px-3">
                    <div class="ts-image">
                        <img src="{{asset('images_gofly/inbox1.png')}}" alt="image" />
                    </div>
                    <div class="ts-content">
                        <h4 class="mar-bottom-5">Suman Dey</h4>
                        <p class="mar-bottom-5">Delhi, India</p>
                        <ul class="list-inline">
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                        </ul>
                        <p class="content mar-0">
                            Our trip to Thailand was nothing short of amazing! The tropical paradise escape package was
                            perfect, and
                            the
                            island-hopping tour was a highlight. The beaches were stunning, and the Thai massage was the
                            cherry on
                            top.
                            Highly recommend!
                        </p>
                        <div class="ts-icon">
                            <i class="fa fa-quote-left" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                <div class="ts-item col-lg-4 px-3">
                    <div class="ts-image">
                        <img src="{{asset('images_gofly/inbox2.png')}}" alt="image" />
                    </div>
                    <div class="ts-content">
                        <h4 class="mar-bottom-5">Aisha, K</h4>
                        <p class="mar-bottom-5">Ahmedabad, Gujarat</p>
                        <ul class="list-inline">
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                        </ul>
                        <p class="content mar-0">
                            Booking our Dubai trip through GoFlyHabibi was a breeze! The luxury escape package offered
                            everything we
                            could have wanted, from stunning accommodations to thrilling desert adventures. It was the
                            vacation of a
                            lifetime
                        </p>
                        <div class="ts-icon">
                            <i class="fa fa-quote-left" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                <div class="ts-item col-lg-4 px-3">
                    <div class="ts-image">
                        <img src="{{asset('images_gofly/inbox3.png')}}" alt="image" />
                    </div>
                    <div class="ts-content">
                        <h4 class="mar-bottom-5">Annette Black</h4>
                        <p class="mar-bottom-5">Texas, USA</p>
                        <ul class="list-inline">
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                        </ul>
                        <p class="content mar-0">
                            <strong> Hotel Equatorial Melaka</strong>
                            The place is in a great location in Gumbet. The area is safe and beautiful. The apartment
                            was comfortable
                            and the host was kind and responsive to our requests.
                            It ony takes few minutes to
                            plan and finalize.
                        </p>
                        <div class="ts-icon">
                            <i class="fa fa-quote-left" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                <div class="ts-item col-lg-4 px-3">
                    <div class="ts-image">
                        <img src="{{asset('images_gofly/inbox4.png')}}" alt="image" />
                    </div>
                    <div class="ts-content">
                        <h4 class="mar-bottom-5">Barry Tuck</h4>
                        <p class="mar-bottom-5">Sydney, Australia</p>
                        <ul class="list-inline">
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                        </ul>
                        <p class="content mar-0">
                            <strong>Japan Tour</strong>
                            "Our family was traveling via bullet train between cities in Japan with our luggage - the
                            location for
                            this hotel made that so easy. Go Fly Habibi's price was fantastic.It ony takes few minutes
                            to
                            plan and finalize."
                        </p>
                        <div class="ts-icon">
                            <i class="fa fa-quote-left" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                <div class="ts-item col-lg-4 px-3">
                    <div class="ts-image">
                        <img src="{{asset('images_gofly/inbox5.png')}}" alt="image" />
                    </div>
                    <div class="ts-content">
                        <h4 class="mar-bottom-5">Russell Thompson</h4>
                        <p class="mar-bottom-5">CEO/Mario Brand</p>
                        <ul class="list-inline">
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                        </ul>
                        <p class="content mar-0">
                            <strong>Singapore Trip</strong>
                            Our Singapore tour was absolutely amazing! From the stunning Marina Bay Sands to the vibrant
                            streets of
                            Chinatown. Thanks to Go Fly Habibi, we had a hassle-free and memorable trip. Can't wait to
                            book our next
                            adventure!
                        </p>
                        <div class="ts-icon">
                            <i class="fa fa-quote-left" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Reviews -->

    <!-- Top Featured -->
    <section class="travelcounter counter2">
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
    <!-- contact Ends -->

    @include('layout_new.footer')

    <!-- *Scripts* -->

    <script src={{asset('js_gofly/bootstrap.min.js')}}></script>
    <script src={{asset('js_gofly/color-switcher.js')}}></script>
    <script src={{asset('js_gofly/plugin.js')}}></script>
    <script src={{asset('js_gofly/main.js')}}></script>
    <script src={{asset('js_gofly/menu.js')}}></script>
    <script src={{asset('js_gofly/custom-swiper2.js')}}></script>
    <script src={{asset('js_gofly/custom-nav.js')}}></script>
    <script src={{asset('js_gofly/custom-date.js')}}></script>

    <script>
        function filterLocationOptions() {
            const input = document.getElementById('location-search-input');
            const filter = input.value.toLowerCase();
            const optionsList = document.getElementById('location-options-list');

            // If input is empty, clear and hide dropdown
            if (filter.length === 0) {
                optionsList.innerHTML = '';
                optionsList.style.display = 'none';
                return;
            }

            // AJAX request to get hotel locations
            fetch(`/hotels/search?query=${encodeURIComponent(input.value)}`)
                .then(response => response.json())
                .then(data => {
                    optionsList.innerHTML = '';
                    if (!data.length) {
                        optionsList.innerHTML = '<div class="option">No results found</div>';
                        optionsList.style.display = 'block';
                        return;
                    }
                    data.slice(0, 5).forEach(location => {
                        // Highlight match
                        const regex = new RegExp(`(${input.value})`, 'ig');
                        const highlighted = location.name.replace(regex, '<span class="highlight">$1</span>');
                        const optionDiv = document.createElement('div');
                        optionDiv.className = 'option';
                        optionDiv.innerHTML = highlighted;
                        optionDiv.setAttribute('data-id', location.id); // Store the location id
                        optionDiv.onclick = function() {
                            input.value = location.name;
                            // If you have a hidden input for location_id, set it here:
                            const hiddenInput = document.getElementById('selected-location-id');
                            if (hiddenInput) hiddenInput.value = location.id;
                            optionsList.style.display = 'none';
                        };
                        optionsList.appendChild(optionDiv);
                    });
                    optionsList.style.display = 'block';
                })
                .catch(() => {
                    optionsList.innerHTML = '<div class="option">Error loading results</div>';
                    optionsList.style.display = 'block';
                });
        }

        function selectLocationOption(option) {
            document.getElementById('location-search-input').value = option.textContent;
            document.getElementById('selected-location-id').value = option.getAttribute('data-value');
            // Optionally, hide the dropdown here if desired
        }
        // Show dropdown on input focus/click
        document.getElementById('location-search-input').addEventListener('focus', function() {
            document.getElementById('location-options-list').style.display = 'block';
        });
        document.getElementById('location-search-input').addEventListener('click', function() {
            document.getElementById('location-options-list').style.display = 'block';
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const selectBox = document.querySelector('.custom-location-select');
            if (!selectBox.contains(event.target)) {
                document.getElementById('location-options-list').style.display = 'none';
            }
        });

        // Hide dropdown when selecting an option
        function selectLocationOption(option) {
            document.getElementById('location-search-input').value = option.textContent;
            document.getElementById('selected-location-id').value = option.getAttribute('data-value');
            document.getElementById('location-options-list').style.display = 'none';
        }

        // Optional: Add some CSS for .highlight
        // .highlight { background: yellow; color: black; }
        // Add this after your other scripts
        // Close hotel-dropdown when clicking outside

        document.addEventListener('mousedown', function(e) {
            // Find all open dropdowns
            document.querySelectorAll('.hotel-dropdown').forEach(function(dropdown) {
                // Find the parent input-box
                const inputBox = dropdown.closest('.input-box');
                // If the click is outside the input-box, close the dropdown
                if (inputBox && !inputBox.contains(e.target)) {
                    dropdown.style.display = 'none';
                    dropdown.style.maxHeight = 0;
                    dropdown.style.opacity = 0;
                }
            });
        });
    </script>
    <script>
  document.addEventListener("DOMContentLoaded", function () {
    const accordions = document.querySelectorAll(".faq-accrodion .accrodion");

    accordions.forEach((accordion) => {
      const title = accordion.querySelector(".accrodion-title");

      title.addEventListener("click", function () {
        // Close all other accordions
        accordions.forEach((item) => {
          if (item !== accordion) {
            item.classList.remove("active");
            item.querySelector(".accrodion-content").style.display = "none";
          }
        });

        // Toggle the clicked one
        const content = accordion.querySelector(".accrodion-content");
        const isOpen = accordion.classList.contains("active");

        if (isOpen) {
          accordion.classList.remove("active");
          content.style.display = "none";
        } else {
          accordion.classList.add("active");
          content.style.display = "block";
        }
      });
    });
  });
</script>
<!-- Custom Booking Modal -->
<div id="customBookingModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 9999;">
    <div style="position: relative; width: 90%; max-width: 600px; margin: 50px auto; background-color: white; border-radius: 5px; padding: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
        <div style="margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px; position: relative;">
            <h3 id="bookingModalLabel" style="margin: 0; font-size: 24px;">Book Your Trip</h3>
            <button type="button" id="closeModal" style="position: absolute; right: 0; top: 0; background: none; border: none; font-size: 24px; cursor: pointer;">&times;</button>
        </div>
        <div>
            <form id="bookingForm" method="POST" action="">
                @csrf
                <input type="hidden" name="plan_id" id="plan_id" value="">
                <input type="hidden" name="plan_name" id="plan_name" value="">
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="name" style="display: block; margin-bottom: 5px; font-weight: bold;">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="email" style="display: block; margin-bottom: 5px; font-weight: bold;">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="phone" style="display: block; margin-bottom: 5px; font-weight: bold;">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="travel_date" style="display: block; margin-bottom: 5px; font-weight: bold;">Travel Date</label>
                    <input type="date" class="form-control" id="travel_date" name="travel_date" required min="{{ date('Y-m-d') }}" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="description" style="display: block; margin-bottom: 5px; font-weight: bold;">Additional Information</label>
                    <textarea class="form-control" id="description" name="description" rows="3" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"></textarea>
                </div>
                
                <div style="text-align: center;">
                    <button type="submit" id="submitBookingBtn" class="biz-btn" style="width: 100%;">
                        <span id="submitBtnText">Submit Booking</span>
                        <span id="submitBtnLoading" style="display: none;">
                            <i class="fa fa-spinner fa-spin" style="margin-right: 5px;"></i> Processing...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Main JS -->
<script src="js/main.js"></script>

<script>
    $(document).ready(function() {
        // Set plan data when modal is opened
        $('.book-now-btn').on('click', function() {
            var planId = $(this).data('plan-id');
            var planName = $(this).data('plan-name');
            $('#plan_id').val(planId);
            $('#plan_name').val(planName);
            $('#bookingModalLabel').text('Book Your Trip to ' + planName);
            
            // Show the custom modal
            $('#customBookingModal').fadeIn(300);
            $('body').css('overflow', 'hidden'); // Prevent scrolling when modal is open
        });
        
        // Close modal when clicking the close button or outside the modal
        $('#closeModal, #customBookingModal').on('click', function(e) {
            if (e.target === this) {
                $('#customBookingModal').fadeOut(300);
                $('body').css('overflow', 'auto'); // Restore scrolling
            }
        });
        
        // Form submission
        $('#bookingForm').on('submit', function(e) {
            e.preventDefault();
            
            // Disable submit button and show loading
            const submitBtn = $('#submitBookingBtn');
            const submitText = $('#submitBtnText');
            const submitLoading = $('#submitBtnLoading');
            
            submitBtn.prop('disabled', true);
            submitText.hide();
            submitLoading.show();
            
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function(response) {
                    // Re-enable button and hide loading
                    submitBtn.prop('disabled', false);
                    submitText.show();
                    submitLoading.hide();
                    
                    // Close modal and show success message
                    $('#customBookingModal').fadeOut(300);
                    $('body').css('overflow', 'auto'); // Restore scrolling
                    alert('Thank you for your booking request! We will contact you shortly.');
                    $('#bookingForm')[0].reset();
                },
                error: function(xhr) {
                    // Re-enable button and hide loading
                    submitBtn.prop('disabled', false);
                    submitText.show();
                    submitLoading.hide();
                    
                    alert('There was an error processing your request. Please try again later.');
                }
            });
        });
    });
</script>
</body>

</html>
