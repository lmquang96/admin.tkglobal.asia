@extends('layouts/master')

@section('title', 'Payable')
@section('content')
<div class="content-wrapper">
  <div class="col-12 mb-4">
      <a href="{{ route('user-detail', ['id' => request('id')]) }}" class="btn {{ request()->route()->getName() == 'user-detail' ? 'btn-primary' : 'btn-secondary' }}" style="border-radius: 4px;">Thông tin tài khoản</a>
      <a href="{{ route('user-payable', ['id' => request('id')]) }}" class="btn {{ request()->route()->getName() == 'user-payable' ? 'btn-primary' : 'btn-secondary' }}" style="border-radius: 4px;">Lịch sử thanh toán</a>
    </div>
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title" style="text-transform: none;">Lịch sử thanh toán của {{ $user->name }} ({{ $user->affiliate_id }}) ({{ $user->email }})</h4>
        <!-- <a href="{{ route('category-create') }}" class="btn btn-primary mb-2 btn-icon-text btn-sm">
          <i class="ti-plus btn-icon-prepend" style="font-size: 0.75rem;"></i>Thêm mới
        </a> -->
        @if (!empty($payable))
        <div class="table-responsive pt-3">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th> Tháng </th>
                <th> Hoa hồng (₫)</th>
                <th> Số dư trước rút (₫)</th>
                <th> Ngày đăng ký rút </th>
                <th> Số tiền rút (₫)</th>
                <th> Thanh toán (₫)</th>
                <th> ngày Thanh toán </th>
                <th> Tạm ứng </th>
                <th> Số dư sau rút (₫)</th>
                {{-- <th></th> --}}
              </tr>
            </thead>
            <tbody>
              @php
              $balance1 = $balance2 = 0;
              @endphp
              @foreach ($payable as $key => $item)
              @php
              $balance1 = $balance2 + $item->amount_pub;
              $balance2 = $balance1 - $item->paid - $item->advance;
              @endphp
              <tr>
                <td> {{ $item->target_month }} </td>
                <td> {{ number_format($item->amount_pub, 0, ',', '.') }} </td>
                <td> {{ number_format($balance1, 0, ',', '.') }} </td>
                <td class="text-center"> {{ $item->submission_date ?? '....-..-..' }} </td>
                <td> {{ number_format($item->amount, 0, ',', '.') }} </td>
                <td> {{ number_format($item->paid, 0, ',', '.') }} </td>
                <td class="text-center"> {{ $item->processing_date ?? '....-..-..' }} </td>
                <td> {{ number_format($item->advance, 0, ',', '.') }} </td>
                <td> {{ number_format($balance2, 0, ',', '.') }} </td>
                {{-- <td> <a class="text-primary" href="#">Chi tiết</a> </td> --}}
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
@endsection
@section('script')
@endsection