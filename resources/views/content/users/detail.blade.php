@extends('layouts/master')

@section('title', 'Campaigns')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Thông tin Tài khoản</h4>
          <form class="form-sample" method="POST" action="{{ route('campaign-store', ['id' => request('id')]) }}">
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
                  <label class="col-sm-3 col-form-label">Danh mục</label>
                  <div class="col-sm-9">
                    <select class="form-select form-select-sm" name="category_id">
                      
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Loại tài khoản</label>
                  <div class="col-sm-9">
                    <select class="form-select form-select-sm" name="category_id">
                      
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
                    <input type="text" class="form-control form-control-sm" name="bank_owner" value="{{ $user->bank_owner }}" />
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
                    <input type="text" class="form-control form-control-sm" name="citizen_id_date" value="{{ $user->citizen_id_date }}" />
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
{{-- <script src="https://cdn.tiny.cloud/1/qwtcr3i872yaa31szulgut1hqw3phs4fbtk85daihd9fljcx/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: 'textarea#tinymce-editor', // Replace this CSS selector to match the placeholder element for TinyMCE
    plugins: 'code table lists fullscreen image emoticons',
    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | fullscreen | image | emoticons',
    height: 600,
    branding: false
  });
</script> --}}
@endsection