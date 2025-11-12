<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title') | TK Global Admin</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('assets/vendors/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <!-- <link rel="stylesheet" href="assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css"> -->
  {{-- <link rel="stylesheet" href="assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css"> --}}
  <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/js/select.dataTables.min.css') }}">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css" rel="stylesheet" type="text/css" />
  @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/assets/css/main.css'])
  @yield('css');
</head>

<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <a class="navbar-brand brand-logo me-5" href="/"><img src="https://tkglobal.asia/assets/img/logo/horizontal.png" class="me-2"
            alt="logo" style="height: 46px;" /></a>
        <a class="navbar-brand brand-logo-mini" href="/"><img src="{{ asset('assets/images/logo-mini.svg') }}"
            alt="logo" /></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="icon-menu"></span>
        </button>
        {{-- <ul class="navbar-nav mr-lg-2">
          <li class="nav-item nav-search d-none d-lg-block">
            <div class="input-group">
              <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                <span class="input-group-text" id="search">
                  <i class="icon-search"></i>
                </span>
              </div>
              <input type="text" class="form-control" id="navbar-search-input" placeholder="Search now"
                aria-label="search" aria-describedby="search">
            </div>
          </li>
        </ul> --}}
        <ul class="navbar-nav navbar-nav-right">
          {{-- <li class="nav-item dropdown">
            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#"
              data-bs-toggle="dropdown">
              <i class="icon-bell mx-0"></i>
              <span class="count"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
              aria-labelledby="notificationDropdown">
              <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-success">
                    <i class="ti-info-alt mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject font-weight-normal">Application Error</h6>
                  <p class="font-weight-light small-text mb-0 text-muted"> Just now </p>
                </div>
              </a>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-warning">
                    <i class="ti-settings mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject font-weight-normal">Settings</h6>
                  <p class="font-weight-light small-text mb-0 text-muted"> Private message </p>
                </div>
              </a>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-info">
                    <i class="ti-user mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject font-weight-normal">New user registration</h6>
                  <p class="font-weight-light small-text mb-0 text-muted"> 2 days ago </p>
                </div>
              </a>
            </div>
          </li> --}}
          <li class="nav-item nav-profile dropdown">
            <div class="me-4">
              <span class="font-bold">{{ auth()->user()->name }}</span>
            </div>
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
              <img src="{{ !empty(auth()->user()->profile->avatar) ? asset('https://tkglobal.asia/assets/img/avatars/' . auth()->user()->profile->avatar) : asset('assets/images/avatar/default.png') }}" alt="profile" />
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              {{-- <a class="dropdown-item">
                <i class="ti-settings text-primary"></i> Settings </a> --}}
              <form id="formLogout" action="{{ route('logout') }}" method="POST">
                @csrf
              <a class="dropdown-item" href="javascript:void(0)"
              onclick="event.preventDefault(); this.closest('#formLogout').submit();">
                <i class="ti-power-off text-primary"></i> Đăng xuất </a>
              </form>
            </div>
          </li>
          {{-- <li class="nav-item nav-settings d-none d-lg-flex">
            <a class="nav-link" href="#">
              <i class="icon-ellipsis"></i>
            </a>
          </li> --}}
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
          data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
              <i class="mdi mdi-home menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('campaigns') }}">
              <i class="mdi mdi-view-grid menu-icon"></i>
              <span class="menu-title">Chiến dịch</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#menu-report" aria-expanded="false"
              aria-controls="menu-report">
              <i class="icon-layout menu-icon"></i>
              <span class="menu-title">Báo cáo</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="menu-report">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('report-performance') }}">Hiệu suất</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('report-order') }}">Chuyển đổi</a>
                </li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('payment-request') }}">
              <i class="mdi mdi-wallet menu-icon"></i>
              <span class="menu-title">Thanh toán</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('payment-advance-history') }}">
              <i class="mdi mdi-arrange-bring-to-front menu-icon"></i>
              <span class="menu-title">Tạm ứng</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('scan-transaction') }}">
              <i class="mdi mdi-scale-balance menu-icon"></i>
              <span class="menu-title">Quét rút</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('users') }}">
              <i class="mdi mdi-account-group menu-icon"></i>
              <span class="menu-title">Publisher</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('utilities-view') }}">
              <i class="mdi mdi-puzzle menu-icon"></i>
              <span class="menu-title">Tiện ích</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#menu-integration" aria-expanded="false"
              aria-controls="menu-report">
              <i class="mdi mdi-lan-connect menu-icon"></i>
              <span class="menu-title">Tích hợp</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="menu-integration">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('integration-campaign') }}">Chiến dịch</a>
                </li>
              </ul>
            </div>
          </li>
        </ul>
      </nav>
      <!-- partial -->
      <div class="main-panel">
        @yield('content')
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="{{ asset('assets/vendors/chart.js/chart.umd.js') }}"></script>
  {{-- <script src="assets/vendors/datatables.net/jquery.dataTables.js"></script> --}}
  <!-- <script src="assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script> -->
  {{-- <script src="assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js"></script> --}}
  {{-- <script src="assets/js/dataTables.select.min.js"></script> --}}
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
  <script src="{{ asset('assets/js/template.js') }}"></script>
  <script src="{{ asset('assets/js/settings.js') }}"></script>
  <script src="{{ asset('assets/js/todolist.js') }}"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="{{ asset('assets/js/jquery.cookie.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/js/dashboard.js') }}"></script>
  <!-- <script src="assets/js/Chart.roundedBarCharts.js"></script> -->
  <!-- End custom js for this page-->
  @yield('script')
  @yield('script-2')
</body>

</html>
