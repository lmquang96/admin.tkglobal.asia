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
                  <label class="col-sm-3 col-form-label">Link tracking</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="tracking_url" />
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
                <div class="form-group">
                  <label for="tinymce-editor">Brand infomation</label>
                  <textarea class="form-control" name="detail" id="tinymce-editor"></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="tinymce-editor" class="font-bold">Traffic Rule</label>
                  <h6 class="text-sm">Được chấp thuận</h6>
                  <div class="row mb-4">
                    @foreach ($trafficRules as $rule)
                    <div class="col-sm-3">
                      <div class="form-check form-check-success">
                        <label class="form-check-label">
                          <input type="checkbox" class="form-check-input allowed" name="allowed_rule[]" value="{{ $rule }}">
                          {{ $rule }}
                        <i class="input-helper"></i></label>
                      </div>
                    </div>
                    @endforeach
                  </div>
                  <h6 class="text-sm">Không được chấp thuận</h6>
                  <div class="row">
                    @foreach ($trafficRules as $rule)
                    <div class="col-sm-3">
                      <div class="form-check form-check-danger">
                        <label class="form-check-label">
                          <input type="checkbox" class="form-check-input not-allowed" name="not_allowed_rule[]" value="{{ $rule }}">
                          {{ $rule }}
                        <i class="input-helper"></i></label>
                      </div>
                    </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">GEO</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="display_geo" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Service</label>
                  <div class="col-sm-9">
                    <div class="form-check">
                      <label class="form-check-label">
                      <select class="form-select form-select-sm" name="cp_type">
                        <option value="CPS">CPS</option>
                        <option value="CPA">CPA</option>
                        <option value="CPQL">CPQL</option>
                        <option value="CPL">CPL</option>
                        <option value="CPR">CPR</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Device</label>
                  <div class="col-sm-9">
                    <div class="row">
                      <div class="col-sm-4">
                        <div class="form-check form-check-success">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input not-allowed" name="device[]" value="Mobile">
                            Mobile
                          <i class="input-helper"></i></label>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-check form-check-success">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input not-allowed" name="device[]" value="Desktop">
                            Desktop
                          <i class="input-helper"></i></label>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-check form-check-success">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input not-allowed" name="device[]" value="Tablet">
                            Tablet
                          <i class="input-helper"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">OS</label>
                  <div class="col-sm-9">
                    <div class="form-check">
                      <label class="form-check-label">
                      <select class="form-select form-select-sm" name="os">
                        <option value="All">Tất cả</option>
                        <option value="Android">Android</option>
                        <option value="IOS">IOS</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="tinymce-editor">Conversion Flow</label>
                  <textarea class="form-control" name="conversion_flow" id="conversion-flow"></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="tinymce-editor">Commission Structure</label>
                  <textarea class="form-control" name="commission_structure" id="commission-structure"></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="tinymce-editor">General Terms</label>
                  <textarea class="form-control" name="terms" id="terms"></textarea>
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
    height: 300,
    branding: false
  });

  tinymce.init({
    selector: 'textarea#conversion-flow', // Replace this CSS selector to match the placeholder element for TinyMCE
    plugins: 'code table lists fullscreen image emoticons',
    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | fullscreen | image | emoticons',
    height: 300,
    branding: false
  });

  tinymce.init({
    selector: 'textarea#commission-structure', // Replace this CSS selector to match the placeholder element for TinyMCE
    plugins: 'code table lists fullscreen image emoticons',
    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | fullscreen | image | emoticons',
    height: 300,
    branding: false
  });

  tinymce.init({
    selector: 'textarea#terms', // Replace this CSS selector to match the placeholder element for TinyMCE
    plugins: 'code table lists fullscreen image emoticons',
    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | fullscreen | image | emoticons',
    height: 300,
    branding: false
  });

  $(document).ready(function() {
    function syncCheckboxes(sourceClass, targetClass) {
      $(`.${sourceClass}`).on('change', function() {
        const value = $(this).val();
        const isChecked = $(this).is(':checked');

        $(`.${targetClass}[value="${value}"]`).prop('disabled', isChecked);
      });
    }

    // Sync cả 2 chiều
    syncCheckboxes('allowed', 'not-allowed');
    syncCheckboxes('not-allowed', 'allowed');
  });
</script>
@endsection