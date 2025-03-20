@extends('layouts/master')

@section('title', 'Campaigns')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Thêm mới chiến dịch</h4>
          <form class="form-sample" method="POST" action="{{ route('campaign-store', ['id' => request('id')]) }}">
            @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Tên chiến dịch</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="name" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Mã chiến dịch</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" disabled />
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Logo chiến dịch</label>
                  <div class="col-sm-9">
                    <div class="input-group d-flex align-items-center">
                      <input type="text" class="form-control form-control-sm" name="image">
                      <div class="input-group-append">
                        <button class="btn btn-sm btn-primary ms-2" type="button">
                          <i class="ti-upload"></i>
                        </button>
                      </div>
                    </div>
                    <a href="#" target="_blank" class="fs-075-rem text-info">Preview</a>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Logo 1:1</label>
                  <div class="col-sm-9">
                    <div class="input-group d-flex align-items-center">
                      <input type="text" class="form-control form-control-sm" name="image_square">
                      <div class="input-group-append">
                        <button class="btn btn-sm btn-primary ms-2" type="button">
                          <i class="ti-upload"></i>
                        </button>
                      </div>
                    </div>
                    <a href="#" target="_blank" class="fs-075-rem text-info">Preview</a>
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
                      @foreach ($categories as $category)
                      <option value="{{ $category->id }}">{{ $category->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Loại chiến dịch</label>
                  <div class="col-sm-4">
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="cp_type" id="cp_type" value="CPS" checked> CPS </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Hoa hồng</label>
                  <div class="col-sm-9">
                    <div class="position-relative">
                      <input type="text" class="form-control form-control-sm" name="commission" />
                      <div class="position-absolute" style="top: 0; right: 0;">
                        <select class="form-select form-select-sm no-chevron bg-primary text-white" style="padding: 0.6rem 0.75rem;" name="commission_type" id="commission_type">
                          <option value="percent">%</option>
                          <option value="vnd">₫</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Hiển thị hh</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="commission_text" />
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Trạng thái</label>
                  <div class="col-sm-9">
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="form-check form-check-success">
                          <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="status" id="active" value="1" checked /> Hoạt động </label>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-check form-check-danger">
                          <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="status" id="deactive" value="0"> Tạm dừng </label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Link trang chủ</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="url" />
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Link tracking</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="tracking_url" />
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
              <div class="col-12">
                <div class="form-group row">
                  <label class="col-sm-1-5 col-form-label">Nội dung</label>
                  <div class="col-sm-10-5">
                    <textarea class="form-control" name="detail" id="tinymce-editor">
                      
                    </textarea>
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
<script src="https://cdn.tiny.cloud/1/qwtcr3i872yaa31szulgut1hqw3phs4fbtk85daihd9fljcx/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: 'textarea#tinymce-editor', // Replace this CSS selector to match the placeholder element for TinyMCE
    plugins: 'code table lists fullscreen image emoticons',
    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | fullscreen | image | emoticons',
    height: 600,
    branding: false
  });
</script>
@endsection