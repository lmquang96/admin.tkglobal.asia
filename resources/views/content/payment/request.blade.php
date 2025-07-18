@extends('layouts/master')

@section('title', 'Payment - Đăng ký rút tiền')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Bảng Đăng Ký Rút Tiền</h4>
          <button class="btn btn-primary mb-2 btn-icon-text btn-sm" data-bs-toggle="modal" data-bs-target="#addAdvancePaymentModal">
            <i class="ti-plus btn-icon-prepend" style="font-size: 0.75rem;"></i>Thêm thủ công
          </button>
          {{-- <p class="card-description"> Add class <code>.table-bordered</code></p> --}}
          <form class="form-sample" method="GET" action="{{ route('payment-request') }}">
            <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                  <label>Tháng</label>
                  <div class="d-flex">
                    <input type="text" id="monthpicker" value="{{ request('month') ?? \Carbon\Carbon::now()->format('Y-m') }}" class="form-control form-control-sm" name="month">
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label>Trạng thái</label>
                  <select class="form-select" name="status">
                    <option value="">Tất cả</option>
                    <option value="1" {{ request('status') === "1" ? 'selected' : ''}}>Chờ xử lý</option>
                    <option value="2" {{ request('status') === "2" ? 'selected' : ''}}>Đã thanh toán</option>
                    <option value="3" {{ request('status') === "3" ? 'selected' : ''}}>Đã hủy</option>
                  </select>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label>Loại tài khoản</label>
                  <select class="form-select" name="account_type">
                    <option value="">Tất cả</option>
                    <option value="Individual" {{ request('account_type') === "Individual" ? 'selected' : ''}}>Individual</option>
                    <option value="Company" {{ request('account_type') === "Company" ? 'selected' : ''}}>Company</option>
                  </select>
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
          @if (!$paymentRequests->isEmpty())
          <div class="table-responsive pt-3">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th> # </th>
                  <th> Tài khoản </th>
                  <th> Loại tài khoản </th>
                  <th> Số CCCD </th>
                  <th> Mã số thuế </th>
                  <th> Ngân hàng </th>
                  <th> Số tài khoản </th>
                  <th> Chi nhánh </th>
                  <th> Trước thuế </th>
                  <th> Thuế </th>
                  <th> Sau thuế </th>
                  <th> Trạng thái </th>
                  <th> Ngày gửi yêu cầu </th>
                  <th> Ngày xử lý </th>
                  <th> Thao tác </th>
                </tr>
              </thead>
              <tbody>
                <tr class="table-warning">
                  <td colspan="8" class="text-left">Tổng ({{ number_format($totalRequests, 0, ',', '.') }})</td>
                  <td class="text-left">{{ number_format($totalAmount, 0, ',', '.') }}</td>
                  <td class="text-left">{{ number_format($totalTax, 0, ',', '.') }}</td>
                  <td class="text-left">{{ number_format($totalAmount - $totalTax, 0, ',', '.') }}</td>
                  <td colspan="4"></td>
                </tr>
                @foreach ($paymentRequests as $key => $paymentRequest)
                @php
                $taxValue = ($paymentRequest->amount > 2000000 && $paymentRequest->account_type != 'Company') ? $paymentRequest->amount * 0.1 : 0;
                @endphp
                <tr>
                  <td>{{ $key + 1 }}</td>
                  <td>
                    <div>{{ $paymentRequest->user->name }}</div>
                    <div class="mt-1">
                      <small class="text-info">{{ $paymentRequest->user->profile->affiliate_id }}</small>
                    </div>
                    <div class="mt-1">
                      <small class="text-primary">{{ $paymentRequest->user->email }}</small>
                    </div>
                  </td>
                  <td>{{ $paymentRequest->user->profile->account_type }}</td>
                  <td>{{ $paymentRequest->user->profile->citizen_id_no ?? 'N/A' }}</td>
                  <td>{{ $paymentRequest->user->profile->tax ?? 'N/A' }}</td>
                  <td>{{ $paymentRequest->user->profile->bank_name ?? 'N/A' }}</td>
                  <td>{{ $paymentRequest->user->profile->bank_number ?? 'N/A' }}</td>
                  <td>{{ $paymentRequest->user->profile->bank_branch ?? 'N/A' }}</td>
                  <td>{{ number_format($paymentRequest->amount, 0, ',', '.') }}</td>
                  <td>{{ number_format($taxValue, 0, ',', '.') }}</td>
                  <td>{{ number_format($paymentRequest->amount - $taxValue, 0, ',', '.') }}</td>
                  <td>
                    @if ($paymentRequest->status == 1)
                    <span class="badge badge-warning me-1">Chờ xử lý</span>
                    @elseif ($paymentRequest->status == 2)
                      <span class="badge badge-success me-1">Đã thanh toán</span>
                    @else
                      <span class="badge badge-danger me-1">Đã hủy</span>
                    @endif
                  </td>
                  <td>{{ $paymentRequest->submission_date ?? 'N/A' }}</td>
                  <td>{{ $paymentRequest->processing_date ?? 'N/A' }}</td>
                  <td>
                    @if ($paymentRequest->status == 1 || $paymentRequest->status == 3)
                    <div>
                      <form action="{{ route('payment-update-status', $paymentRequest->id) }}" method="POST" class="payment-update-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="2" >
                        <input type="hidden" name="code" value="{{ $paymentRequest->code }}" >
                        <button class="badge badge-success mt-1">Thanh toán</button>
                      </form>
                    </div>
                    @endif
                    @if ($paymentRequest->status == 2 || $paymentRequest->status == 3)
                    <div>
                      <form action="{{ route('payment-update-status', $paymentRequest->id) }}" method="POST" class="payment-update-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="1" >
                        <input type="hidden" name="code" value="{{ $paymentRequest->code }}" >
                        <button class="badge badge-warning mt-1">Chờ xử lý</button>
                      </form>
                    </div>
                    @endif
                    @if ($paymentRequest->status == 1 || $paymentRequest->status == 2)
                    <div>
                      <form action="{{ route('payment-update-status', $paymentRequest->id) }}" method="POST" class="payment-update-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="3" >
                        <input type="hidden" name="code" value="{{ $paymentRequest->code }}" >
                        <button class="badge badge-danger mt-1">Hủy yêu cầu</button>
                      </form>
                    </div>
                    @endif
                  </td>
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
</div>
<div class="modal fade" id="addAdvancePaymentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Nhập thông tin rút tiền</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form-add-payment-request" class="form-sample" method="POST">
          @csrf
          <div class="mb-3">
            <label class="col-form-label">Tháng rút tiền</label>
            <x-month-picker />
          </div>
          <div class="mb-3">
            <label class="col-form-label">Affiliate ID</label>
            <input type="text" class="form-control form-control-sm" name="affiliate_id">
          </div>
          <div class="mb-3">
            <label class="col-form-label">Số tiền rút</label>
            <input type="number" class="form-control form-control-sm" name="amount">
          </div>
          <div class="mb-3">
            <label class="col-form-label">Đợt</label>
            <select name="comment" class="form-select">
              <option value="phase 1">1</option>
              <option value="phase 2">2</option>
            </select>
          </div>
          <div class="mb-3">
            <button type="submit" class="btn btn-primary">Lưu</button>
          </div>
        </form>
      </div>
      {{-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="submit" class="btn btn-primary">Lưu</button>
      </div> --}}
    </div>
  </div>
</div>
@endsection
@section('script-2')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

  $(".payment-update-form").submit(function (e) { 
    e.preventDefault();
    
    Swal.fire({
      title: "Bạn đã chắc chưa?",
      text: "Hãy xác nhận yêu cầu của mình!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Chắc chắn",
      cancelButtonText: "Suy nghĩ lại",
    }).then((result) => {
      if (result.isConfirmed) {
        $(this).unbind('submit').submit();
      }
    });
  });

  $("#form-add-payment-request").submit(function(e) {
    e.preventDefault();
    // ajax call
    $.ajax({
      type: "POST",
      url: "{{ route('payment-add-request') }}",
      data: $(this).serialize(),
      success: function (response) {
        window.location.reload();
      }
    });
  });
</script>
@endsection
