
   
    document.addEventListener("DOMContentLoaded", function () {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });


    document.addEventListener('DOMContentLoaded', function () {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

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


  function removeSegmentRow(button) {
    const segment = button.closest('.city-segment');
    const allSegments = document.querySelectorAll('.city-segment');
    if (allSegments.length > 1) {
      segment.remove();
    } else {
      alert("At least one segment is required.");
    }
  }

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
    newToText.addEventListener('input', function () {
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
          <button type="button" class="biz-btn add-segment-btn" style="margin-top: 35px; background-color:#ec7b34; margin-right:20px; width:130px;" onclick="addSegmentRow()">
            +Add More
          </button>`;

        if (segments.length > 1) {
          actionCol.innerHTML += `
            <button type="button" class="biz-btn btn-sm remove-segment-btn"  title="Remove" style="margin-top: 40px; background-color:#ec7b34; width:40px; height:40px; display: flex; align-items: center; justify-content: center;" onclick="removeSegmentRow(this)">
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
    beforeShowDay: function (t) {
      const today = new Date();
      const target = new Date(t);

      today.setHours(0, 0, 0, 0);
      target.setHours(0, 0, 0, 0);

      return target < today
        ? [false, '', 'Unavailable']
        : [true, '', ''];
    }
  });
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
         // ✅ Auto-fill "From" field if this is the "To" field
    if (input.name === 'to_where_text_multicity[]') {
        setToAndFromValues(input);
    }
    }


  document.addEventListener("DOMContentLoaded", function () {
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

  document.addEventListener("DOMContentLoaded", function () {
    // Counter buttons
    document.querySelectorAll(".traveler-btn").forEach(function (btn) {
      btn.addEventListener("click", function () {
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


  document.addEventListener("DOMContentLoaded", function () {
    // Counter buttons specific to oneway
    document.querySelectorAll(".traveler-btn[data-context='oneway']").forEach(function (btn) {
      btn.addEventListener("click", function () {
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

    document.getElementById("travelerSummaryOneway").innerText = summary;

    const hiddenInput = document.getElementById("travelSummaryInputOneway");
    if (hiddenInput) hiddenInput.value = summary;
  }

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

    this.action = `/gofly/search?${queryString}`; // ✅ Use your route here

    this.submit();
});

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

    this.action = `/gofly/search?${queryString}`; // ✅ Use your route here

    this.submit();
});

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

    this.action = `/gofly/search/roundtrip?${queryString}`;
    this.submit();
});

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
  form.action = `/gofly/search/multicity?${queryParams.toString()}`;
  form.submit();
});



// window.addEventListener("DOMContentLoaded", function () {
//     const urlParams = new URLSearchParams(window.location.search);

//     function getAllWithPrefix(prefix) {
//         const results = [];
//         for (const [key, value] of urlParams.entries()) {
//             if (key.startsWith(prefix)) {
//                 results.push(value);
//             }
//         }
//         return results;
//     }

//     const fromCities = getAllWithPrefix("from_where_text_multicity");
//     const fromCodes = getAllWithPrefix("from_where_multicity_unique");
//     const toCities = getAllWithPrefix("to_where_text_multicity");
//     const toCodes = getAllWithPrefix("to_where_multicity_unique");
//     const dates = getAllWithPrefix("departure_date_multicity_unique");

//     const travelClass = urlParams.get("travel_class_multicity_unique") || "ECONOMY";
//     const adult = urlParams.get("adult_count_multicity_unique") || "1";
//     const child = urlParams.get("child_count_multicity_unique") || "0";
//     const infant = urlParams.get("infant_count_multicity_unique") || "0";
//     const preferredAirline = urlParams.get("preferred_airline_multicity_unique") || "None";

//     const passengerSummary = `${adult} Adult${child > 0 ? `, ${child} Child` : ""}${infant > 0 ? `, ${infant} Infant` : ""} | ${travelClass}`;

//     let outputHtml = `
//         <div class="multicity-summary-container px-4 d-flex flex-wrap align-items-center justify-content-center px-3 py-2 text-white" style="background-color:#2f2f2f;">
//     `;

//     // Routes
//     for (let i = 0; i < fromCities.length; i++) {
//         outputHtml += `
//             <div class="d-flex align-items-center me-4">
//                 <div class="text-center me-2">
//                     <div><strong>${fromCodes[i]}</strong></div>
//                     <div style="font-size: 12px;">${fromCities[i]}</div>
//                 </div>
//                 <div class="mx-2">✈</div>
//                 <div class="text-center me-2">
//                     <div><strong>${toCodes[i]}</strong></div>
//                     <div style="font-size: 12px;">${toCities[i]}</div>
//                 </div>
//             </div>
//         `;

//         // Add separator after each route, unless it's the last route and other sections follow
//         if (i < fromCities.length - 1 || true) {
//             outputHtml += `<div class="vr mx-2" style="height: 40px;"></div>`;
//         }
//     }

//     // Passengers & Class
//     outputHtml += `
//         <div class="me-4">
//             <strong>Passengers & Class</strong><br>
//             <small>${passengerSummary}</small>
//         </div>
//         <div class="vr mx-2" style="height: 40px;"></div>
//     `;

//     // Preferred Airline
//     outputHtml += `
//         <div class="me-4">
//             <strong>Preferred Airline</strong><br>
//             <small>${preferredAirline}</small>
//         </div>
//         <div class="vr mx-2" style="height: 40px;"></div>
//     `;

//     // Modify button
//     outputHtml += `
//         <div class="ms-4">
//            <button class="btn btn-outline-light btn-sm" onclick="toggleSearchTab('mc')">MODIFY SEARCH</button>
//         </div>
//     </div>`;

//  const container = document.getElementById("multicity-summary-display");
// if (container) {
//     container.innerHTML = outputHtml;
//     container.style.display = "block";
// }

// // 🔻 Hide oneway summary
// const onewaySummary = document.getElementById("oneway-summary-display");
// if (onewaySummary) {
//     onewaySummary.style.display = "none";
// }

// });




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



window.addEventListener("DOMContentLoaded", () => {
  function getQueryParams() {
    const params = new URLSearchParams(window.location.search);
    const data = {};
    for (const [key, value] of params.entries()) {
      const arrayKeyMatch = key.match(/^([^\[]+)(\[(.*?)\])?$/);
      if (!arrayKeyMatch) continue;
      const baseKey = arrayKeyMatch[1];
      const index = arrayKeyMatch[3];
      if (!data[baseKey]) data[baseKey] = [];
      if (index === undefined || index === "") {
        data[baseKey].push(value);
      } else {
        data[baseKey][parseInt(index)] = value;
      }
    }
    return data;
  }

  const params = getQueryParams();
  const form = document.getElementById("searchFormMulticityUnique");

  const totalSegments = params["from_where_multicity_unique"]?.length || 0;
  const existingHtmlRows = 2;
  const rowsToAdd = Math.max(0, totalSegments - existingHtmlRows);

  // Step 1: Add extra rows if needed
  for (let i = 0; i < rowsToAdd; i++) {
    if (typeof addSegmentRow === "function") {
      addSegmentRow();
    }
  }

  // Step 2: Fill segment input values
  for (let i = 0; i < totalSegments; i++) {
    const fromCode = params["from_where_multicity_unique"]?.[i] || "";
    const fromText = params["from_where_text_multicity"]?.[i] || "";
    const toCode = params["to_where_multicity_unique"]?.[i] || "";
    const toText = params["to_where_text_multicity"]?.[i] || "";
    const depDate = params["departure_date_multicity_unique"]?.[i] || "";

    const suffix = i === 0 ? "[]" : `[${i}]`;

    const fromCodeInput = document.querySelector(`input[name="from_where_multicity_unique${suffix}"]`);
    const fromTextInput = document.querySelector(`input[name="from_where_text_multicity${suffix}"]`);
    const toCodeInput = document.querySelector(`input[name="to_where_multicity_unique${suffix}"]`);
    const toTextInput = document.querySelector(`input[name="to_where_text_multicity${suffix}"]`);

    if (fromCodeInput) fromCodeInput.value = fromCode;
    if (fromTextInput) fromTextInput.value = fromText;
    if (toCodeInput) toCodeInput.value = toCode;
    if (toTextInput) toTextInput.value = toText;

    // ✅ Date input fix (handles [] and [2], [3], ...)
    let depDateInput;
    if (i <= 1) {
      const allDateInputs = document.querySelectorAll('input[name="departure_date_multicity_unique[]"]');
      depDateInput = allDateInputs[i];
    } else {
      depDateInput = document.querySelector(`input[name="departure_date_multicity_unique[${i}]"]`);
    }
    if (depDateInput) depDateInput.value = depDate;
  }

  // Step 2.5: Set third departure date input (optional)
  const thirdDepDate = params["departure_date_multicity_unique"]?.[2] || "";
  const thirdDepInput = document.querySelector('input[name="departure_date_multicity_unique[2]"]');
  if (thirdDepInput) {
    thirdDepInput.value = thirdDepDate;
  }

  // Step 3: Travelers and class
  const getInt = (val) => parseInt(val || "0");
  const adults = getInt(params["adult_count_multicity_unique"]?.[0]);
  const children = getInt(params["child_count_multicity_unique"]?.[0]);
  const infants = getInt(params["infant_count_multicity_unique"]?.[0]);
  const totalPax = adults + children + infants;

  // Set traveler inputs
  document.getElementById("adultCountInputMulticityUnique").value = adults;
  document.getElementById("childCountInputMulticityUnique").value = children;
  document.getElementById("infantCountInputMulticityUnique").value = infants;

  // Set traveler labels
  document.getElementById("adultCountMulticityUnique").textContent = adults;
  document.getElementById("childCountMulticityUnique").textContent = children;
  document.getElementById("infantCountMulticityUnique").textContent = infants;

  // Set travel class and summary
  const classText = params["travel_class_multicity_unique"]?.[0] || "Economy";
  const travelerSummary = `${totalPax} Travelers - ${classText}`;
  document.getElementById("travelerSummaryMulticityUnique").textContent = travelerSummary;
  document.getElementById("travelClassMulticityUnique").value = classText;
  document.getElementById("travelSummaryInputMulticityUnique").value = travelerSummary;
});


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

///one way

// window.addEventListener("DOMContentLoaded", function () {
//   const urlParams = new URLSearchParams(window.location.search);
//   const fromText = urlParams.get("from_where_text") || "";
//   const fromCode = urlParams.get("from_where_oneway[]") || "";
//   const toText = urlParams.get("to_where_text") || "";
//   const toCode = urlParams.get("to_where_oneway[]") || "";
//   const depDate = urlParams.get("departure_date_oneway") || "";

//   const travelClass = urlParams.get("travel_class_oneway") || "Economy";
//   const adult = urlParams.get("adult_count_oneway") || "1";
//   const child = urlParams.get("child_count_oneway") || "0";
//   const infant = urlParams.get("infant_count_oneway") || "0";

//   const passengerSummary = `${adult} Adult${child > 0 ? `, ${child} Child` : ""}${infant > 0 ? `, ${infant} Infant` : ""} | ${travelClass}`;

//   const outputHtml = `
//     <div class="multicity-summary-container px-4 d-flex flex-wrap align-items-center justify-content-center px-3 py-2 text-white" style="background-color:#2f2f2f;">
//       <div class="d-flex align-items-center me-4">
//         <div class="text-center me-2">
//           <div><strong>${fromCode}</strong></div>
//           <div style="font-size: 12px;">${fromText}</div>
//         </div>
//         <div class="mx-2">✈</div>
//         <div class="text-center me-2">
//           <div><strong>${toCode}</strong></div>
//           <div style="font-size: 12px;">${toText}</div>
//         </div>
//       </div>
//       <div class="vr mx-2" style="height: 40px;"></div>
//       <div class="me-4">
//         <strong>Departure Date</strong><br>
//         <small>${depDate}</small>
//       </div>
//       <div class="vr mx-2" style="height: 40px;"></div>
//       <div class="me-4">
//         <strong>Passengers & Class</strong><br>
//         <small>${passengerSummary}</small>
//       </div>
//        <div class="vr mx-2" style="height: 40px;"></div>
//       <div class="me-4">
//         <strong>Preferrred Airline</strong><br>
//         <small>None</small>
//       </div>
//       <div class="vr mx-2" style="height: 40px;"></div>
//       <div class="ms-4">
//         <button class="btn btn-outline-light btn-sm" onclick="toggleSearchTab('ow')">MODIFY SEARCH</button>
//       </div>
//     </div>`;
// // ✅ Inject into DOM
//   const container = document.getElementById("oneway-summary-display");
//   if (container) {
//     container.innerHTML = outputHtml;
//     container.style.display = "block";
//   }
// });

function toggleSearchTab(tabKey) {
  const tabIds = {
    rt: "roundtrip",
    ow: "oneway",
    mc: "multicity"
  };

  const bannerForm = document.querySelector(".banner-form");
  if (bannerForm && bannerForm.classList.contains("d-none")) {
    bannerForm.classList.remove("d-none");
  }

  document.querySelectorAll(".nav-link").forEach(link => link.classList.remove("active"));
  document.querySelectorAll(".tab-pane").forEach(pane => pane.classList.remove("active", "show"));

  const navLink = document.getElementById(tabKey);
  const tabPaneId = tabIds[tabKey];

  if (navLink) navLink.classList.add("active");
  if (tabPaneId) {
    const pane = document.getElementById(tabPaneId);
    if (pane) {
      pane.classList.add("active", "show");
      pane.scrollIntoView({ behavior: "smooth" });
    }
  }

  if (tabKey === "ow") {
    const params = new URLSearchParams(window.location.search);

    document.querySelector("input[name='from_where_text']").value = params.get("from_where_text") || "";
    document.querySelector("input[name='from_where_oneway[]']").value = params.getAll("from_where_oneway[]")[0] || "";
    document.querySelector("input[name='to_where_text']").value = params.get("to_where_text") || "";
    document.querySelector("input[name='to_where_oneway[]']").value = params.getAll("to_where_oneway[]")[0] || "";
    document.querySelector("input[name='departure_date_oneway']").value = params.get("departure_date_oneway") || "";

    document.getElementById("adultCountOneway").textContent = params.get("adult_count_oneway") || "1";
    document.getElementById("adultCountInputOneway").value = params.get("adult_count_oneway") || "1";

    document.getElementById("childCountOneway").textContent = params.get("child_count_oneway") || "0";
    document.getElementById("childCountInputOneway").value = params.get("child_count_oneway") || "0";

    document.getElementById("infantCountOneway").textContent = params.get("infant_count_oneway") || "0";
    document.getElementById("infantCountInputOneway").value = params.get("infant_count_oneway") || "0";

    document.getElementById("travelClassOneway").value = params.get("travel_class_oneway") || "Economy";

    const summary = `${params.get("adult_count_oneway") || 1} Adult${params.get("child_count_oneway") > 0 ? `, ${params.get("child_count_oneway")} Child` : ""}${params.get("infant_count_oneway") > 0 ? `, ${params.get("infant_count_oneway")} Infant` : ""} - ${params.get("travel_class_oneway") || "Economy"}`;
    document.getElementById("travelerSummaryOneway").textContent = summary;
    document.getElementById("travelSummaryInputOneway").value = summary;
  }

  const multicitySummary = document.getElementById("multicity-summary-display");
  if (multicitySummary) multicitySummary.style.display = "none";

  const onewaySummary = document.getElementById("oneway-summary-display");
  if (onewaySummary) onewaySummary.style.display = "none";
}



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


    function showSegment(index) {
        const tabs = document.querySelectorAll(".segment-content");
        tabs.forEach((tab, i) => {
            tab.style.display = i === index ? "block" : "none";
        });

        const tabHeaders = document.querySelectorAll(".segment-tabs li");
        tabHeaders.forEach((li, i) => {
            li.classList.toggle('active', i === index);
        });
    }

