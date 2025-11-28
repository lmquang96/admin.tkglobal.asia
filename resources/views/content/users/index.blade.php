@extends('layouts/master')

@section('title', 'Publishers')
@section('content')
<div class="content-wrapper">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Bảng danh sách publisher</h4>
        <!-- <a href="{{ route('category-create') }}" class="btn btn-primary mb-2 btn-icon-text btn-sm">
          <i class="ti-plus btn-icon-prepend" style="font-size: 0.75rem;"></i>Thêm mới
        </a> -->
        <form id="form-scan" class="form-sample" method="GET">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label>Tên</label>
                  <input type="text" class="form-control form-control-sm" name="name" placeholder="Nhập tên" value="{{ request('name') }}">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Email</label>
                  <input type="email" class="form-control form-control-sm" name="email" placeholder="Nhập email" value="{{ request('email') }}">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Affiliate ID</label>
                  <input type="text" class="form-control form-control-sm" name="affiliate_id" placeholder="Nhập affiliate id" value="{{ request('affiliate_id') }}">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="d-block text-white">.</label>
                  <button type="submit" class="btn btn-primary mb-2 btn-icon-text">
                    <i class="mdi mdi-filter btn-icon-prepend"></i>Lọc
                  </button>
                </div>
              </div>
            </div>
          </form>
        <div class="table-responsive pt-3">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center"> # </th>
                <th> Tên </th>
                <th> Email </th>
                <th width="50"> Affiliate ID </th>
                <th class="text-center"> Chi tiết </th>
              </tr>
            </thead>
            <tbody>
              @foreach ($users as $key => $user)
              <tr>
                <td class="text-center"> {{ $key + 1 }} </td>
                <td> {{ $user->name }} </td>
                <td> {{ $user->email }} </td>
                <td> {{ $user->affiliate_id }} </td>
                <td class="text-center">
                  <button class="btn btn-primary btn-rounded btn-icon" data-url="{{ route('user-detail', ['id' => $user->user_id]) }}">
                    <i class="ti-file"></i>
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
@endsection
@section('script')
<script>
  $("table button").click(function(){
    window.location.href = $(this).data('url');
  });
</script>
@endsection
