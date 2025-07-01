@extends('layouts/master')

@section('title', 'Payment - Lịch sử tạm ứng')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Bảng Lịch sử tạm ứng</h4>
          {{-- <p class="card-description"> Add class <code>.table-bordered</code></p> --}}
          <form class="form-sample" method="GET" action="{{ route('payment-advance-history') }}">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label>Tháng</label>
                  <div class="input-group input-daterange align-items-center">
                    <x-month-range-picker />
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
          <button class="btn btn-primary mb-2 btn-icon-text btn-sm" data-bs-toggle="modal" data-bs-target="#addAdvancePaymentModal">
            <i class="ti-plus btn-icon-prepend" style="font-size: 0.75rem;"></i>Thêm mới
          </button>
          @if (!$data->isEmpty())
          <div class="table-responsive pt-3">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th> # </th>
                  <th> Tài khoản </th>
                  <th> Tháng tạm ứng </th>
                  <th> Số tiền(₫) </th>
                  <th> Ngày tạo </th>
                  {{-- <th> Trạng thái </th>
                  <th> Ngày cập nhật </th> --}}
                  <th> Ghi chú </th>
                  {{-- <th> Thao tác </th> --}}
                </tr>
              </thead>
              <tbody>
                <tr class="table-warning">
                  <td colspan="3"></td>
                  <td>{{ number_format($totalAmount, 0, ',', '.') }}</td>
                  <td colspan="5"></td>
                </tr>
                @foreach ($data as $key => $history)
                <tr>
                  <td>{{ $key + 1 }}</td>
                  <td>
                    <div>{{ $history->user->name }}</div>
                    <div class="mt-1">
                      <small class="text-info">{{ $history->user->profile->affiliate_id }}</small>
                    </div>
                    <div class="mt-1">
                      <small class="text-primary">{{ $history->user->email }}</small>
                    </div>
                  </td>
                  <td>{{ $history->target_month }}</td>
                  <td>{{ number_format($history->amount, 0, ',', '.') }}</td>
                  <td>{{ $history->created_at }}</td>
                  {{-- <td>
                    @if ($history->status == '1')
                      <span class="badge badge-success me-1">Đã trừ</span>
                    @else
                      <span class="badge badge-primary me-1">Chưa trừ</span>
                    @endif
                  </td>
                  <td>{{ $history->status == '1' ? $history->updated_at : 'N/A
                  ' }}</td> --}}
                  <td>{{ $history->note }}</td>
                  {{-- <td>
                    <form class="form-delete-advance-payment" action="{{ route('payment-advance-delete', $history->id) }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-outline-warning btn-sm">Xóa</button>
                    </form>
                  </td> --}}
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
        <h5 class="modal-title" id="exampleModalLabel">Nhập thông tin tạm ứng</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form-add-advance-payment" class="form-sample" method="POST">
          @csrf
          <div class="mb-3">
            <label class="col-form-label">Tháng tạm ứng</label>
            <x-month-picker />
          </div>
          <div class="mb-3">
            <label class="col-form-label">Affiliate ID</label>
            <input type="text" class="form-control form-control-sm" name="affiliate_id">
          </div>
          <div class="mb-3">
            <label class="col-form-label">Số tiền tạm ứng</label>
            <input type="number" class="form-control form-control-sm" name="amount">
          </div>
          <div class="mb-3">
            <label class="col-form-label">Ghi chú (nếu có)</label>
            <textarea class="form-control form-control-sm" name="note" rows="4"></textarea>
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
@if (session('message'))
<div class="position-fixed top-[50px] end-0 p-3" style="z-index: 11">
  <div id="liveToast" class="toast fade show align-items-center text-white bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        {{ session('message') }}
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>
@endif
@endsection
@section('script-2')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  $("#form-add-advance-payment").submit(function(e) {
    e.preventDefault();
    // ajax call
    $.ajax({
      type: "POST",
      url: "{{ route('payment-advance-save') }}",
      data: $(this).serialize(),
      success: function (response) {
        window.location.reload();
      }
    });
  });

  $(".form-delete-advance-payment").submit(function(e) {
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

  setTimeout(() => {
    if ($("#liveToast").length) {
      $("#liveToast").removeClass('show');
      $("#liveToast").addClass('hiden');
    }
  }, 3000);
</script>
@endsection