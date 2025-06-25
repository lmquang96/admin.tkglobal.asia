@extends('layouts/master')

@section('title', 'Reports')
@section('content')
<div class="content-wrapper position-relative overflow-auto">
  <div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Bảng Lịch sử quét rút</h4>
          <form id="form-scan" class="form-sample" method="POST">
            <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                  <label>Khoảng ngày</label>
                  <div class="d-flex">
                    <input type="text" id="monthpicker" value="{{ request('month') ?? \Carbon\Carbon::now()->format('Y-m') }}" class="form-control form-control-sm" name="month">
                  </div>
                </div>
              </div>
              @if (Auth::user()->id == 8)
              <div class="col-md-2">
                <div class="form-group">
                  <label>Campagin ID</label>
                  <input type="text" class="form-control form-control-sm" name="campaignId">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="d-block text-white">.</label>
                  <button type="submit" class="btn btn-primary mb-2 btn-icon-text">
                    <i class="mdi mdi-puzzle btn-icon-prepend"></i>Quét
                  </button>
                </div>
              </div>
              @endif
            </div>
          </form>
          @if (!$transaction->isEmpty())
          <div class="table-responsive pt-3">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th> # </th>
                  <th>Tài khoản</th>
                  <th>Chiến dịch</th>
                  <th>Số tiền của Pub</th>
                  <th>Số tiền của TK</th>
                  <th>Thời điểm quét</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr class="table-warning">
                  <td colspan="3" class="text-left">Tổng ({{ count($transaction) }})</td>
                  <td>
                    {{ number_format($totalAmountPub, 0, ',', '.') }}
                  </td>
                  <td>
                    {{ number_format($totalAmountSys, 0, ',', '.') }}
                  </td>
                  <td colspan="2">
                    {{ number_format($totalAmountPub + $totalAmountSys, 0, ',', '.') }}
                  </td>
                </tr>
                @foreach ($transaction as $key => $row)
                <tr>
                  <td> {{ $key + 1 }} </td>
                  <td>
                    <div>{{ $row->user->name }}</div>
                    <div class="mt-1">
                      <small class="text-info">{{ $row->user->profile->affiliate_id }}</small>
                    </div>
                    <div class="mt-1">
                      <small class="text-primary">{{ $row->user->email }}</small>
                    </div>
                  </td>
                  <td>{{ $row->campaign->name }}</td>
                  <td>{{ number_format($row->amount_pub, 0, ',', '.') }}</td>
                  <td>{{ number_format($row->amount_sys, 0, ',', '.') }}</td>
                  <td>{{ $row->created_at }}</td>
                  <td><a href="{{ route('report-order', ['status' => 'Paid', 'affiliate_id' => $row->user->profile->affiliate_id, 'paid_at' => request('month')]) }}" style="color: blueviolet;">Xem đơn hàng</a></td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          @else
          <x-empty-data />
          @endif
        </div>
      </div>
    </div>
  </div>
  <div class="position-absolute hidden scanning-popup">
    <div class="inner">
      <div style="padding: 10px;" class="h-full">
        <div class="msg">Scanning</div>
        <div id="console" class="overflow-hidden h-full">
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
<script>
  var intervalID = window.setInterval(updateScreen, 200);
  var consoleBox = document.getElementById("console");

  var txt = [
    "FORCE: XX0022. ENCYPT://000.222.2345",
    "TRYPASS: ********* AUTH CODE: ALPHA GAMMA: 1___ PRIORITY 1",
    "RETRY: REINDEER FLOTILLA",
    "Z:> /FALKEN/GAMES/TICTACTOE/ EXECUTE -PLAYERS 0",
    "================================================",
    "Priority 1 // local / scanning...",
    "scanning ports...",
    "BACKDOOR FOUND (23.45.23.12.00000000)",
    "BACKDOOR FOUND (13.66.23.12.00110000)",
    "BACKDOOR FOUND (13.66.23.12.00110044)",
    "...",
    "...",
    "BRUTE.EXE -r -z",
    "...locating vulnerabilities...",
    "...vulnerabilities found...",
    "MCP/> DEPLOY CLU",
    "SCAN: __ 0100.0000.0554.0080",
    "SCAN: __ 0020.0000.0553.0080",
    "SCAN: __ 0001.0000.0554.0550",
    "SCAN: __ 0012.0000.0553.0030",
    "SCAN: __ 0100.0000.0554.0080",
    "SCAN: __ 0020.0000.0553.0080",
  ]

  var docfrag = document.createDocumentFragment();

  function updateScreen() {
    //Shuffle the "txt" array
    txt.push(txt.shift());
    //Rebuild document fragment
    txt.forEach(function(e) {
      var p = document.createElement("p");
      p.textContent = e;
      docfrag.appendChild(p);
    });
    //Clear DOM body
    while (consoleBox.firstChild) {
      consoleBox.removeChild(consoleBox.firstChild);
    }
    consoleBox.appendChild(docfrag);
  }

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

  $('#monthpicker').datepicker({
    autoclose: true,
    minViewMode: 1,
    format: "yyyy-mm",
    language: 'vi',
    defaultDate: new Date(),
  });

  $("#form-scan").submit(function (e) { 
    e.preventDefault();
    $(".scanning-popup").removeClass("hidden");
    $(".scanning-popup .inner").css({
      "height": "300px",
    });
    setTimeout(() => {
      $(".scanning-popup .msg").css({
        "animationName": "blink",
      });
    }, 1000);
    $.ajax({
      type: "POST",
      url: "{{ route('scan-transaction-scan') }}",
      data: {
        _token: "{{ csrf_token() }}",
        month: $('#monthpicker').val(),
        campaignId: $("input[name='campaignId']").val()
      },
      success: function (response) {
        setTimeout(() => {
          window.location.reload();
        }, 3000);
      },
      error: function (error) {

      },
      complete: function (error) {
        setTimeout(() => {
          $(".scanning-popup .msg").css({
            "animationName": "none",
          });
          $(".scanning-popup .inner").css({
            "height": "0px",
          });
          setTimeout(() => {
            $(".scanning-popup").addClass("hidden");
          }, 1000);
        }, 2000);
      },
    });
  });

  $("#monthpicker").change(function (e) { 
    e.preventDefault();
    let url = new URL(window.location.href);
    url.searchParams.set('month', $(this).val());
    window.location.href = url.toString();
  });
</script>
@endsection