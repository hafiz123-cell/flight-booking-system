$('#date-range0').dateRangePicker({
  autoClose: true,
  singleDate: true,
  showShortcuts: false,
  singleMonth: true,
  showTopbar: false,
  extraClass: 'reserved-form',

  beforeShowDay: function (t) {
    const today = new Date();
    const target = new Date(t);

    // Remove time from both dates
    today.setHours(0, 0, 0, 0);
    target.setHours(0, 0, 0, 0);

    // Disable only if target is before today
    if (target < today) {
      return [false, '', 'Unavailable'];
    }
    return [true, '', ''];
  }
});
 
$('#date-range0-oneway').dateRangePicker({
  autoClose: true,
  singleDate: true,
  showShortcuts: false,
  singleMonth: true,
  showTopbar: false,
  extraClass: 'reserved-form',

  beforeShowDay: function (t) {
    const today = new Date();
    const target = new Date(t);

    // Remove time from both dates
    today.setHours(0, 0, 0, 0);
    target.setHours(0, 0, 0, 0);

    // Disable only if target is before today
    if (target < today) {
      return [false, '', 'Unavailable'];
    }
    return [true, '', ''];
  }
});
$('#date-range1').dateRangePicker({
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

    if (target < today) {
      return [false, '', 'Unavailable'];
    }
    return [true, '', ''];
  }
});
$('.departure_date_multicity_unique').dateRangePicker({
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

    if (target < today) {
      return [false, '', 'Unavailable'];
    }
    return [true, '', ''];
  }
});
$('.departure_date_multicity_unique1').dateRangePicker({
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

    if (target < today) {
      return [false, '', 'Unavailable'];
    }
    return [true, '', ''];
  }
});
