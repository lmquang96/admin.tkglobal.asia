@extends('layouts/master')

@section('title', 'Tiện ích')
@section('css')
  <link rel="stylesheet" href="{{ asset('assets/css/utilities.css') }}">
@endsection
@section('content')
  <div class="content-wrapper">
    <div class="col-lg-12 m-auto grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Danh sách các tiện ích</h4>
          <div class="row">
            <div class="col-12">
              <div class="row portfolio-grid">
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                  <figure class="effect-text-in" data-bs-toggle="modal" data-bs-target="#uploadShopeeModal">
                    <img src="https://tinhocnews.com/wp-content/uploads/2024/06/2-16.png" alt="image">
                    <figcaption>
                      <h4>Shopee Upload</h4>
                      <p>Tải lên dữ liệu đơn hàng shopee từ file báo cáo chuyển đổi</p>
                    </figcaption>
                  </figure>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="uploadShopeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">File Uploader</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="h-[200px] w-full border-2 border-dashed border-indigo-500 content-center text-center text-indigo-500 cursor-pointer hover:bg-gray-100" onclick="handleUploadShopeeFile()">
            <div>Files Supported: XLSX, CSV</div>
            <div class="mt-4">
              <i class="mdi mdi-cloud-upload btn-icon-prepend text-[64px] leading-[40px]"></i>
            </div>
            <div>Browse file to upload</div>
          </div>
          <input type="file" id="shopee-upload-file-input" hidden />
          <div class="bg-indigo-100 flex mt-6 p-1 gap-x-4 hidden" id="shopee-upload-file-alert">
            <div>
              <i class="mdi mdi-file-document btn-icon-prepend text-[40px] text-indigo-500"></i>
            </div>
            <div class="self-center">
              <div id="shopee-upload-file-name">
                image_03.jpg
              </div>
              <div class="text-sm text-gray-500" id="shopee-upload-file-size">
                96.47 KB
              </div>
            </div>
          </div>
          <div class="w-full mt-4 hidden" id="shopee-upload-file-loader">
            <div class='h-1.5 w-full bg-indigo-100 overflow-hidden'>
              <div class='progress w-full h-full bg-indigo-500 left-right'></div>
            </div>
          </div>
          <div class="text-end">
            <button class="btn btn-primary mt-4 hidden" id="shopee-upload-file-button">Submit</button>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('script')
  <script>
    function handleUploadShopeeFile() {
      $("#shopee-upload-file-input").click();
    }

    $("#shopee-upload-file-input").change(function() {
      const files = $(this)[0].files;
      if(files.length > 0) {
        const file = files[0];
        const fileSize = formatFileSize(file.size);
        $("#shopee-upload-file-name").html(file.name);
        $("#shopee-upload-file-size").html(fileSize);
        $("#shopee-upload-file-button").removeClass('hidden');
        $("#shopee-upload-file-alert").removeClass('hidden');
      } else {
        $("#shopee-upload-file-button").addClass('hidden');
        $("#shopee-upload-file-alert").addClass('hidden');
      }
    });

    $("#shopee-upload-file-button").click(function() {
      $("#shopee-upload-file-button").prop('disabled', true)
      $("#shopee-upload-file-button").html('Loading');
      $("#shopee-upload-file-loader").removeClass('hidden');
      const files = $("#shopee-upload-file-input")[0].files;
      const formData = new FormData();
      const csrfToken = $('meta[name="csrf-token"]').attr('content');
      formData.append('_token', csrfToken);
      formData.append('file', files[0]);

      $.ajax({
        url: '/utilities/shopee-upload',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          if (response.status == 200) {
            Swal.fire({
              title: 'Tải lên thành công!',
              icon: 'success',
              showConfirmButton: false,
              timer: 1500
            })
            document.activeElement.blur();
            $('#uploadShopeeModal').modal('hide')
          }
        },
        error: function(xhr, status, error) {
          Swal.fire({
            title: 'Tải lên thất bại!',
            icon: 'error',
            showConfirmButton: false,
            timer: 1500
          })
          document.activeElement.blur();
          $('#uploadShopeeModal').modal('hide')
        },
        complete: function(xhr, status) {
          $("#shopee-upload-file-button").prop('disabled', false);
          $("#shopee-upload-file-button").html('Submit');
          $("#shopee-upload-file-loader").addClass('hidden');
        }
      });
    });

    function formatFileSize(bytes) {
      if (bytes === 0) return '0 Bytes';
      const k = 1024;
      const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
      const i = Math.floor(Math.log(bytes) / Math.log(k));
      return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
  </script>
@endsection
