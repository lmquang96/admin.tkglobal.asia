<div class="input-group input-daterange align-items-center">
  <input type="text" value="{{ request('fromMonth') ?? \Carbon\Carbon::now()->format('Y-m') }}" class="form-control form-control-sm" name="fromMonth">
  <div class="input-group-addon ms-2 me-2">Đến</div>
  <input type="text" value="{{ request('toMonth') ?? \Carbon\Carbon::now()->format('Y-m') }}" class="form-control form-control-sm" name="toMonth">
</div>
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
<script>
  $.fn.datepicker.dates['vi'] = {
    days: ["T2", "T3", "T4", "T5", "T6", "T7", "CN"],
    daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
    daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
    months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
    monthsShort: ["Th 1", "Th 2", "Th 3", "Th 4", "Th 5", "Th 6", "Th 7", "Th 8", "Th 9", "Th 10", "Th 11", "Th 12"],
    today: "Today",
    clear: "Clear",
    format: "mm/dd/yyyy",
    titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
    weekStart: 0
  };

  $('.input-daterange input').each(function() {
    $(this).datepicker({
      autoclose: true,
      minViewMode: 1,
      format: "yyyy-mm",
      language: 'vi',
      defaultDate: new Date(),
    });
  });

  $('.input-single-date input').each(function() {
    $(this).datepicker({
      autoclose: true,
      minViewMode: 1,
      format: "yyyy-mm",
      language: 'vi',
      defaultDate: new Date(),
    });
  });
</script>
@endsection