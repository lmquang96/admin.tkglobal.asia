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
              <div class="col-md-4">
                <div class="form-group">
                  <label>Khoảng ngày</label>
                  <x-date-range-input name="date" date="{{ request('date') }}" />
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Nhóm theo</label>
                  <div class="input-group d-flex align-items-center">
                    <div class="input-group-prepend">
                      <button class="btn btn-sm btn-outline-primary dropdown-toggle me-2 btn-group-item" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-id="campaign_id">Chiến dịch</button>
                      <div class="dropdown-menu">
                        <div class="dropdown-item curs-pointer group-dropdown-item" data-id="user_id">Tài khoản</div>
                        <div class="dropdown-item curs-pointer group-dropdown-item" data-id="order_time">Ngày tháng</div>
                      </div>
                      <input type="hidden" name="group" value="{{ request('group', 'campaign_id') }}">
                    </div>
                    <input type="text" class="form-control form-control-sm" name="keyword" placeholder="Nhập từ khóa" value="{{ request('keyword') }}" {{ request('group') == 'order_time' ? 'disabled' : ''}}>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="d-block text-white">.</label>
                  <button type="submit" class="btn btn-primary mb-2 btn-icon-text">
                    <i class="ti-filter btn-icon-prepend" style="font-size: 0.75rem;"></i>Lọc
                  </button>
                </div>
              </div>
            </div>
          </form>
          @if (!$data->isEmpty())
          <div class="table-responsive pt-3">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th> # </th>
                  <th>{{ request('group') == 'user_id' ? 'Tài khoản' : 'Chiến dịch' }}</th>
                  <th>Lượt click</th>
                  <th>Chuyển đổi</th>
                  <th>Giá trị chuyển đổi</th>
                  <th>Hoa hồng</th>
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
                  <td colspan="2"></td>
                </tr>
                </tr>
                @foreach ($data as $key => $row)
                @php
                if (request('group', 'campaign_id') == 'user_id') {
                  $clickByGroupId = isset($clicks[$row->user_id]) ? $clicks[$row->user_id]['cnt'] : 0;
                } else if (request('group', 'campaign_id') == 'campaign_id') {
                  $clickByGroupId = isset($clicks[$row->campaign->id]) ? $clicks[$row->campaign->id]['cnt'] : 0;
                } else if (request('group', 'campaign_id') == 'order_time') {
                  $clickByGroupId = isset($clicks[$row->date]) ? $clicks[$row->date]['cnt'] : 0;
                }
                $groupValue = '';
                @endphp
                <tr>
                  <td> {{ $key + 1 }} </td>
                  <td>
                    @if (request('group', 'campaign_id') == 'user_id')
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
                    @elseif(request('group', 'campaign_id') == 'campaign_id')
                    @php
                    $groupValue = $row->campaign->name;
                    @endphp
                      {{ $row->campaign->name }}
                    @elseif(request('group', 'campaign_id') == 'order_time')
                      {{ $row->date }}  
                    @endif
                  </td>
                  <td> {{ number_format($clickByGroupId, 0, ',', '.') }} </td>
                  <td> {{ number_format($row->cnt, 0, ',', '.') }} </td>
                  <td> {{ number_format($row->total_price, 0, ',', '.') }} </td>
                  <td> {{ number_format($row->total_com, 0, ',', '.') }} </td>
                  <td> {{ number_format($clickByGroupId > 0 ? ($row->cnt / $clickByGroupId) * 100 : 0, 1, ',', '.') }}% </td>
                  <td style="width: 80px;">
                    <a href="{{ route('report-order', ['groupValue' => $groupValue, 'date' => (request('group', 'campaign_id') == 'order_time' ? $row->date . ' - ' . $row->date : request('date')), 'group' => request('group', 'campaign_id'), 'affiliate_id' => request('group', 'campaign_id') == 'user_id' ? $row->user->profile->affiliate_id : '']) }}" style="color: blueviolet;">
                      Chuyển đổi
                    </a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
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
</script>
@endsection