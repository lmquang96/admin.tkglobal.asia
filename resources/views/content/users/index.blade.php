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
