@extends('layouts/master')

@section('title', 'Campaigns')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 mb-4">
      <a href="{{ route('user-detail', ['id' => request('id')]) }}" class="btn {{ request()->route()->getName() == 'user-detail' ? 'btn-primary' : 'btn-secondary' }}" style="border-radius: 4px;">Thông tin tài khoản</a>
      <a href="{{ route('user-payable', ['id' => request('id')]) }}" class="btn {{ request()->route()->getName() == 'user-payable' ? 'btn-primary' : 'btn-secondary' }}" style="border-radius: 4px;">Lịch sử thanh toán</a>
    </div>
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Thông tin Tài khoản</h4>
          <form class="form-sample" method="POST" action="#">
            @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Họ và tên</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="name" value="{{ $user->name }}" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Email</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="email" value="{{ $user->email }}" disabled />
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Số điện thoại</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="phone" value="{{ $user->phone }}" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Địa chỉ</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="address" value="{{ $user->address }}" />
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Thành phố</label>
                  <div class="col-sm-9">
                    <select id="city" class="select2 form-select" name="city"></select>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Loại tài khoản</label>
                  <div class="col-sm-9">
                    <select id="account_type" class="select2 form-select" name="account_type">
                      <option value="Individual"
                        {{ !empty($user->profile->account_type) && $user->profile->account_type == 'Individual' ? 'selected' : '' }}>
                        Cá nhân</option>
                      <option value="Company"
                        {{ !empty($user->profile->account_type) && $user->profile->account_type == 'Company' ? 'selected' : '' }}>
                        Doanh nghiệp</option>
                      <option value="Individual Business"
                        {{ !empty($user->profile->account_type) && $user->profile->account_type == 'Individual Business' ? 'selected' : '' }}>
                        Doanh nghiệp Cá Thể</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Affiliate ID</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="affiliate_id" value="{{ $user->affiliate_id }}" disabled />
                  </div>
                </div>
              </div>
            </div>
            {{-- <div class="row">
              <div class="col-12">
                <div class="form-group row">
                  <label class="col-sm-1-5 col-form-label">Mô tả ngắn</label>
                  <div class="col-sm-10-5">
                    <textarea class="form-control" name="description" rows="4">
                      {{ $campaignDetail->description }}
                    </textarea>
                  </div>
                </div>
              </div>
            </div> --}}
            <div class="row">
              <div class="col-md-12 text-right">
                <div class="form-group">
                  <input type="submit" class="btn btn-primary" value="Lưu" />
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="card mt-6">
        <div class="card-body">
          <h4 class="card-title">Thông tin Thanh toán</h4>
          <form class="form-sample" method="POST" action="{{ route('campaign-store', ['id' => request('id')]) }}">
            @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Chủ tài khoản</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="bank_owner" value="{{ $user->bank_owner }}" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Số tài khoản</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="bank_number" value="{{ $user->bank_number }}" />
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Ngân hàng</label>
                  <div class="col-sm-9">
                    <select id="bank" class="select2 form-select" name="bank"></select>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Chi nhánh</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="bank_branch" value="{{ $user->bank_branch }}" />
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Số CMT/CCCD</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="citizen_id_no" value="{{ $user->citizen_id_no }}" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Ngày cấp</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="citizen_id_date" value="{{ $user->citizen_id_date ? \Carbon\Carbon::parse($user->citizen_id_date)->format('Y-m-d') : '' }}" />
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Nơi cấp</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="citizen_id_place" value="{{ $user->citizen_id_place }}" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Mã số thuế</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="tax" value="{{ $user->tax }}" />
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 text-right">
                <div class="form-group">
                  <input type="submit" class="btn btn-primary" value="Lưu" />
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script>
  $(document).ready(function() {
      $.ajax({
        type: "GET",
        url: "https://provinces.open-api.vn/api/",
        success: function(response) {
          let profileCity = '{{ auth()->user()->profile->city_code ?? null }}'
          let html = '<option value="">-- Chọn --</option>';
          $.each(response, function(index, item) {
            html +=
              `<option value="${item.code}|${item.name}" ${profileCity == item.code ? 'selected' : ''}>${item.name}</option>`;
          });

          $('#city').html(html);
        }
      });

      $.ajax({
        type: "GET",
        url: "https://api.vietqr.io/v2/banks",
        success: function(response) {
          let profileCity = '{{ auth()->user()->profile->bank_code ?? null }}'
          let html = '<option value="">-- Chọn --</option>';
          $.each(response.data, function(index, item) {
            html +=
              `<option value="${item.code}|${item.name}" ${profileCity == item.code ? 'selected' : ''}>${item.name}</option>`;
          });

          $('#bank').html(html);
        }
      });
    });
</script>
@endsection