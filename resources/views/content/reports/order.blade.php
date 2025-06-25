@extends('layouts/master')

@section('title', 'Reports')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Bảng Báo Cáo Chuyển Đổi</h4>
          {{-- <p class="card-description"> Add class <code>.table-bordered</code></p> --}}
          <form class="form-sample" method="GET" action="{{ route('report-order') }}">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label>Khoảng ngày</label>
                  <x-date-range-input name="date" date="{{ request('date') }}" />
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
                  <label>Affiliate ID</label>
                  <input type="text" class="form-control form-control-sm" name="affiliate_id" value="{{ request('affiliate_id') }}">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Chiến dịch</label>
                  <input type="text" class="form-control form-control-sm" list="datalistOptions" name="keyword" value="{{ request('group') == 'campaign_id' ? request('groupValue') : request('keyword') }}">
                  <datalist id="datalistOptions">
                    @foreach ($campaigns as $campaign)
                    <option value="{{ $campaign->name }}">
                    @endforeach
                  </datalist>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>ID đơn hàng</label>
                  <input type="text" class="form-control form-control-sm" name="order_code" value="{{ request('order_code') }}">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Mã sản phẩm</label>
                  <input type="text" class="form-control form-control-sm" name="product_code" value="{{ request('product_code') }}">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Tên sản phẩm</label>
                  <input type="text" class="form-control form-control-sm" name="product_name" value="{{ request('product_name') }}">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Sub ID 1</label>
                  <input type="text" class="form-control form-control-sm" name="sub1" value="{{ request('sub1') }}">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Sub ID 2</label>
                  <input type="text" class="form-control form-control-sm" name="sub2" value="{{ request('sub2') }}">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Sub ID 3</label>
                  <input type="text" class="form-control form-control-sm" name="sub3" value="{{ request('sub3') }}">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Sub ID 4</label>
                  <input type="text" class="form-control form-control-sm" name="sub4" value="{{ request('sub4') }}">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Tháng đối soát</label>
                  <div class="d-flex">
                    <input type="text" id="monthpicker" value="{{ request('paid_at') ?? \Carbon\Carbon::now()->format('Y-m') }}" class="form-control form-control-sm" name="paid_at">
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  {{-- <label class="d-block text-white">.</label> --}}
                  <button type="submit" class="btn btn-primary btn-sm mb-2 btn-icon-text w-full">
                    <i class="ti-filter btn-icon-prepend"></i>Lọc
                  </button>
                </div>
              </div>
            </div>
          </form>
          <div>
            <a href="{{ route('report-order-export', request()->all()) }}" target="_blank" class="btn btn-primary btn-icon-text">
              <i class="mdi mdi-download"></i>
              Export
            </a>
          </div>
          @if (isset($data) && !$data->isEmpty())
          <div class="table-responsive pt-3">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>ID chuyển đổi</th>
                  <th>Thời gian phát sinh</th>
                  <th>Thời gian click</th>
                  <th>Chiến dịch</th>
                  <th>Tài khoản</th>
                  <th>ID đơn hàng</th>
                  <th>Mã sản phẩm</th>
                  <th>Tên sản phẩm</th>
                  <th>Giá trị đơn hàng(₫)</th>
                  <th>Hoa hồng Pub(₫)</th>
                  <th>Hoa hồng TK(₫)</th>
                  <th>Hoa hồng tổng(₫)</th>
                  <th>Trạng thái</th>
                  <th>Sub ID 1</th>
                  <th>Sub ID 2</th>
                  <th>Sub ID 3</th>
                  <th>Sub ID 4</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                <tr class="table-warning">
                  <td colspan="8" class="text-left">Tổng ({{ number_format($totalConversion, 0, ',', '.') }})</td>
                  <td>{{ number_format($totalPrice, 0, ',', '.') }}</td>
                  <td>{{ number_format($totalCom, 0, ',', '.') }}</td>
                  <td>{{ number_format($totalComSys, 0, ',', '.') }}</td>
                  <td>{{ number_format($totalCom + $totalComSys, 0, ',', '.') }}</td>
                  <td colspan="6"></td>
                </tr>
                </tr>
                @foreach ($data as $key => $row)
                <tr>
                  <td>
                    {{ $row->code }}
                  </td>
                  <td>
                    {{ $row->order_time }}
                  </td>
                  <td>
                    {{ $row->click->created_at }}
                  </td>
                  <td>
                    {{ $row->campaign->name }}
                  </td>
                  <td>
                    <div>{{ $row->user->name }}</div>
                    <div class="mt-1">
                      <small class="text-info">{{ $row->user->profile->affiliate_id }}</small>
                    </div>
                    <div class="mt-1">
                      <small class="text-primary">{{ $row->user->email }}</small>
                    </div>
                  </td>
                  <td>
                    {{ $row->order_code }}
                  </td>
                  <td>
                    {{ $row->product_code }}
                  </td>
                  <td>
                    {{ $row->product_name }}
                  </td>
                  <td>
                    {{ number_format($row->unit_price, 0, ',', '.') }}
                  </td>
                  <td>
                    {{ number_format($row->commission_pub, 0, ',', '.') }}
                  </td>
                  <td>
                    {{ number_format($row->commission_sys, 0, ',', '.') }}
                  </td>
                  <td>
                    {{ number_format($row->commission_pub + $row->commission_sys, 0, ',', '.') }}
                  </td>
                  <td>
                    @if ($row->status == 'Pending')
                      <span class="badge badge-warning me-1">Tạm duyệt</span>
                    @elseif ($row->status == 'Approved')
                      <span class="badge badge-success me-1">Đã duyệt</span>
                    @else
                      <span class="badge badge-danger me-1">Đã hủy</span>
                    @endif
                  </td>
                  <td>
                    {{ $row->click->linkHistory->sub1 ?? 'N/A' }}
                  </td>
                  <td>
                    {{ $row->click->linkHistory->sub2 ?? 'N/A' }}
                  </td>
                  <td>
                    {{ $row->click->linkHistory->sub3 ?? 'N/A' }}
                  </td>
                  <td>
                    {{ $row->click->linkHistory->sub4 ?? 'N/A' }}
                  </td>
                  <td>
                    @if ($row->status == 'Pending')
                      <div><button class="badge badge-success">Duyệt đơn</button></div>
                      <div><button class="badge badge-danger mt-1">Hủy đơn</button></div>
                    @elseif ($row->status == 'Approved')
                      <div><button class="badge badge-warning">Tạm duyệt</button></div>
                      <div><button class="badge badge-danger mt-1">Hủy đơn</button></div>
                    @else
                      <div><button class="badge badge-success">Duyệt đơn</button></div>
                      <div><button class="badge badge-warning mt-1">Tạm duyệt</button></div>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <x-paginate :paginator="$data" />
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