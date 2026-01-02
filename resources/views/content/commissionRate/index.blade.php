@extends('layouts/master')

@section('title', 'Commission Rate')
@section('content')
<div class="content-wrapper">
  @if(session()->has('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @elseif(session()->has('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Bảng danh sách tỉ lệ hoa hồng</h4>
        <button class="btn btn-primary mb-2 btn-icon-text btn-sm" data-bs-toggle="modal" data-bs-target="#addCommissionRateModal">
            <i class="ti-plus btn-icon-prepend" style="font-size: 0.75rem;"></i>Thêm thủ công
          </button>
        <div class="table-responsive pt-3">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center"> # </th>
                <th> Tên </th>
                <th class="text-center"> Tỉ lệ </th>
                <th width="50"> Thao tác </th>
              </tr>
            </thead>
            <tbody>
              @foreach ($rates as $key => $rate)
              <tr>
                <td class="text-center"> {{ $key + 1 }} </td>
                <td> {{ $rate->user->name }} </td>
                <td class="text-center">
                  {{ $rate->rate * 100 }}%
                </td>
                <td class="text-center">
                  <button class="btn btn-primary btn-rounded btn-icon btn-edit-rate" data-bs-toggle="modal" data-bs-target="#addCommissionRateModal" data-rate="{{ $rate->rate }}" data-account="{{ $rate->user_id }}">
                    <i class="ti-pencil"></i>
                  </button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="addCommissionRateModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Nhập thông tin rút tiền</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form-add-commission-rate" class="form-sample" method="POST">
          @csrf
          <div class="mb-3">
            <label class="col-form-label">Account ID</label>
            <select name="account_id" class="form-select">
                @foreach($users as $user)
              <option value="{{ $user->id }}">{{ $user->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="col-sm-3 col-form-label">Tỉ lệ</label>
            <div class="input-group">
                <input type="number" class="form-control form-control-sm" name="rate">
                <div class="input-group-append">
                    <span class="input-group-text">%</span>
                </div>
            </div>
          </div>
          <div class="mb-3">
            <button type="submit" class="btn btn-primary">Lưu</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script>
  $("#form-add-commission-rate").submit(function(e) {
    e.preventDefault();
    $.ajax({
      type: "POST",
      url: "{{ route('commission-rate-create') }}",
      data: $(this).serialize(),
      success: function (response) {
        window.location.reload();
      }
    });
  });

  $(".btn-edit-rate").click(function(e){
    e.preventDefault();
    $("input[name=rate]").val($(this).attr('data-rate') * 100);
    $("select[name=account_id]").val($(this).attr('data-account'))
  });
</script>
@endsection
