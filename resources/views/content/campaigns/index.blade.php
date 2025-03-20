@extends('layouts/master')

@section('title', 'Campaigns')
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
        <h4 class="card-title">Bảng danh sách chiến dịch</h4>
        <a href="{{ route('campaign-create') }}" class="btn btn-primary mb-2 btn-icon-text btn-sm">
          <i class="ti-plus btn-icon-prepend" style="font-size: 0.75rem;"></i>Thêm mới
        </a>
        </p>
        <div class="table-responsive pt-3">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center"> # </th>
                <th> Tên </th>
                <th> Danh mục </th>
                <th> Hoa hồng </th>
                <th class="text-center"> Trạng thái </th>
                <th width="50"> Thao tác </th>
              </tr>
            </thead>
            <tbody>
              @foreach ($campaigns as $key => $campaign)
              <tr>
                <td class="text-center"> {{ $key + 1 }} </td>
                <td> {{ $campaign->name }} </td>
                <td> {{ $campaign->category->name }} </td>
                <td> {{ $campaign->commission_text }} </td>
                <td class="text-center">
                  <label class="badge badge-{{ $campaign->status == 1 ? 'success' : 'danger' }}">
                    {{ $campaign->status == 1 ? 'Hoạt động' : 'Đang dừng' }}
                  </label>
                </td>
                <td class="text-center">
                  <button class="btn btn-primary btn-rounded btn-icon" data-url="{{ route('campaign-edit', ['id' => $campaign->id]) }}">
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
@endsection
@section('script')
<script>
$("table button").click(function(){
  window.location.href = $(this).data('url');
});
</script>
@endsection