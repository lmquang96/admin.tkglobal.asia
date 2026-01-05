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
        <form class="form-sample" method="GET" action="{{ route('campaigns') }}">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label>Tên</label>
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
                  <label>Danh mục</label>
                  <select class="form-select" name="category_id">
                    <option value="">Tất cả</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ request('status') === $category->id ? 'selected' : ''}}>{{ $category->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Trạng thái</label>
                  <select class="form-select" name="status">
                    <option value="">Tất cả</option>
                    <option value="1" {{ request('status') === "1" ? 'selected' : ''}}>Hoạt động</option>
                    <option value="0" {{ request('status') === "0" ? 'selected' : ''}}>Đang dừng</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="d-block text-white">.</label>
                  <button type="submit" class="btn btn-primary mb-2 btn-icon-text">
                    <i class="ti-filter btn-icon-prepend" style="font-size: 0.75rem;"></i>Lọc
                  </button>
                </div>
              </div>
            </div>
          </form>
        @if (isset($campaigns) && !$campaigns->isEmpty())
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
                <td class="text-center"> {{ $key + $campaigns->firstItem() }} </td>
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
        <x-paginate :paginator="$campaigns" />
        @else
        <div class="text-center">Không tìm thấy dữ liệu phù hợp</div>
        @endif
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
