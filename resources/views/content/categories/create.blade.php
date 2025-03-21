@extends('layouts/master')

@section('title', 'Campaigns')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Thêm mới danh mục</h4>
          <form class="form-sample" method="POST" action="{{ route('category-store', ['id' => request('id')]) }}">
            @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label">Tên danh mục</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="name" />
                  </div>
                </div>
              </div>
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
@endsection