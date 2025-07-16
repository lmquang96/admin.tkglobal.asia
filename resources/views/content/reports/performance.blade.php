@extends('layouts/master')

@section('title', 'Reports')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Bảng Báo Cáo Hiệu Suất</h4>
          {{-- <p class="card-description"> Add class <code>.table-bordered</code></p> --}}
          <form class="form-sample" method="GET" action="{{ route('report-performance') }}">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label>Khoảng ngày</label>
                  <x-date-range-input name="date" date="{{ request('date') }}" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Nhóm theo</label>
                  {{-- <div class="input-group d-flex align-items-center">
                    <div class="input-group-prepend">
                      <button class="btn btn-sm btn-outline-primary dropdown-toggle me-2 btn-group-item" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-id="campaign_id">Chiến dịch</button>
                      <div class="dropdown-menu">
                        <div class="dropdown-item curs-pointer group-dropdown-item" data-id="user_id">Tài khoản</div>
                        <div class="dropdown-item curs-pointer group-dropdown-item" data-id="order_time">Thời gian</div>
                      </div>
                      <input type="hidden" name="group" value="{{ request('group', 'campaign_id') }}">
                    </div>
                    <input type="text" class="form-control form-control-sm" name="keyword" placeholder="Nhập từ khóa" value="{{ request('keyword') }}" {{ request('group') == 'order_time' ? 'disabled' : ''}}>
                  </div> --}}
                  <select class="form-select" name="group">
                    <option value="order_time" {{ request('group', 'order_time') == 'order_time' ? 'selected' : ''}}>Thời gian</option>
                    <option value="campaign_id" {{ request('group', 'order_time') == 'campaign_id' ? 'selected' : ''}}>Chiến dịch</option>
                    <option value="user_id" {{ request('group', 'order_time') == 'user_id' ? 'selected' : ''}}>Tài khoản</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Trạng thái</label>
                  <select class="form-select" name="status">
                    <option value="">Tất cả</option>
                    <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : ''}}>Đã Thanh toán</option>
                    <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : ''}}>Đã duyệt</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : ''}}>Tạm duyệt</option>
                    <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : ''}}>Đã hủy</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>By business</label>
                  <select class="form-select" name="by_business">
                    <option value="">Tất cả</option>
                    <option value="TKFUNNEL" {{ request('by_business') == 'TKFUNNEL' ? 'selected' : ''}}>TKFUNNEL</option>
                    <option value="TKGLOBAL" {{ request('by_business') == 'TKGLOBAL' ? 'selected' : ''}}>TKGLOBAL</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Chiến dịch</label>
                  <input type="text" class="form-control form-control-sm" list="datalistOptions" name="keyword" value="{{ request('keyword') }}">
                  <datalist id="datalistOptions">
                    @foreach ($campaigns as $campaign)
                    <option value="{{ $campaign->name }}">
                    @endforeach
                  </datalist>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Affiliate ID</label>
                  <div class="d-flex">
                    <input type="text" value="{{ request('affiliate_id') }}" class="form-control form-control-sm" name="affiliate_id">
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label>Tháng đối soát</label>
                  <div class="d-flex">
                    <input type="text" id="monthpicker" value="{{ request('paid_at') }}" class="form-control form-control-sm" name="paid_at">
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label class="d-block text-white">.</label>
                  <button type="submit" class="btn btn-primary mb-2 btn-icon-text">
                    <i class="ti-filter btn-icon-prepend" style="font-size: 0.75rem;"></i>Lọc
                  </button>
                </div>
              </div>
            </div>
          </form>
          <div>
            <a href="{{ route('report-performance-export', request()->all()) }}" target="_blank" class="btn btn-primary btn-icon-text">
              <i class="mdi mdi-download"></i>
              Export
            </a>
          </div>
          @if (!$data->isEmpty())
          <div class="table-responsive pt-3">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th> # </th>
                  <th>{{ request('group') == 'user_id' ? 'Tài khoản' : (request('group') == 'order_time' ? 'Thời gian' : 'Chiến dịch') }}</th>
                  <th>Lượt click</th>
                  <th>Chuyển đổi</th>
                  <th>Giá trị chuyển đổi</th>
                  <th>Hoa hồng Pub(₫)</th>
                  <th>Hoa hồng TK(₫)</th>
                  <th>Tỉ lệ</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr class="table-warning">
                  <td colspan="2" class="text-left">Tổng ({{ count($data) }})</td>
                  <td>{{ number_format($clickCount, 0, ',', '.') }}</td>
                  <td>{{ number_format($totalConversion, 0, ',', '.') }}</td>
                  <td>{{ number_format($totalPrice, 0, ',', '.') }}</td>
                  <td>{{ number_format($totalCom, 0, ',', '.') }}</td>
                  <td>{{ number_format($totalComSys, 0, ',', '.') }}</td>
                  <td colspan="2"></td>
                </tr>
                </tr>
                @foreach ($data as $key => $row)
                @php
                if (request('group', 'order_time') == 'user_id') {
                  $clickByGroupId = isset($clicks[$row->user_id]) ? $clicks[$row->user_id]['cnt'] : 0;
                } else if (request('group', 'order_time') == 'campaign_id') {
                  $clickByGroupId = isset($clicks[$row->campaign->id]) ? $clicks[$row->campaign->id]['cnt'] : 0;
                } else if (request('group', 'order_time') == 'order_time') {
                  $clickByGroupId = isset($clicks[$row->date]) ? $clicks[$row->date]['cnt'] : 0;
                }
                $groupValue = '';
                @endphp
                <tr>
                  <td> {{ $key + 1 }} </td>
                  <td>
                    @if (request('group', 'order_time') == 'user_id')
                    @php
                    $groupValue = $row->user->name;
                    @endphp
                      <div>{{ $row->user->name }}</div>
                      <div class="mt-1">
                        <small class="text-info">{{ $row->user->profile->affiliate_id }}</small>
                      </div>
                      <div class="mt-1">
                        <small class="text-primary">{{ $row->user->email }}</small>
                      </div>
                    @elseif(request('group', 'order_time') == 'campaign_id')
                    @php
                    $groupValue = $row->campaign->name;
                    @endphp
                      {{ $row->campaign->name }}
                    @elseif(request('group', 'order_time') == 'order_time')
                      {{ $row->date }}  
                    @endif
                  </td>
                  <td> {{ number_format($clickByGroupId, 0, ',', '.') }} </td>
                  <td> {{ number_format($row->cnt, 0, ',', '.') }} </td>
                  <td> {{ number_format($row->total_price, 0, ',', '.') }} </td>
                  <td> {{ number_format($row->total_com, 0, ',', '.') }} </td>
                  <td> {{ number_format($row->total_com_sys, 0, ',', '.') }} </td>
                  <td> {{ number_format($clickByGroupId > 0 ? ($row->cnt / $clickByGroupId) * 100 : 0, 1, ',', '.') }}% </td>
                  <td style="width: 80px;">
                    <a href="{{ route('report-order', ['groupValue' => $groupValue, 'date' => (request('group', 'order_time') == 'order_time' ? $row->date . ' - ' . $row->date : request('date')), 'group' => request('group', 'order_time'), 'affiliate_id' => request('affiliate_id'), 'keyword' => request('keyword'), 'status' => request('status'), 'paid_at' => request('paid_at')]) }}" style="color: blueviolet;">
                      Chuyển đổi
                    </a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          @else
          <div class="text-center">Không tìm thấy dữ liệu phù hợp</div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
<script>
  $(".group-dropdown-item").click(function(){
    let groupNewText = $(this).html();
    let groupNewId = $(this).attr('data-id');
    let groupOldText = $(".btn-group-item").html();
    let groupOldId = $(".btn-group-item").attr('data-id');
    $(".btn-group-item").html(groupNewText);
    $(".btn-group-item").attr('data-id', groupNewId);
    $(this).html(groupOldText);
    $(this).attr('data-id', groupOldId);
    $("input[name='group']").val(groupNewId);
    if (groupNewId == 'order_time'){
      $("input[name='keyword']").prop('disabled', true);
    } else {
      $("input[name='keyword']").prop('disabled', false);
    }
  });

  $(document).ready(function () {
    let group = $("input[name='group']").val();
    if (group != $(".btn-group-item").attr('data-id')){
      $(".group-dropdown-item[data-id="+group+"]").click();
    }
  });

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
</script>
@endsection