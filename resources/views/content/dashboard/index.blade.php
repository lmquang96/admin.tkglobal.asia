@extends('layouts/master')

@section('title', 'Dashboard')
@section('content')
<div class="content-wrapper">
  <form class="form-sample" method="GET" action="{{ route('report-performance') }}">
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label>Khoảng ngày</label>
          <x-date-range-input name="dashboard_date" date="{{ request('date') }}" autoApply="0" />
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label>By business</label>
          <select class="form-select" name="by_business" id="by_business">
            <option value="">Tất cả</option>
            <option value="TKFUNNEL" {{ request('by_business') == 'TKFUNNEL' ? 'selected' : ''}}>TKFUNNEL</option>
            <option value="TKGLOBAL" {{ request('by_business') == 'TKGLOBAL' ? 'selected' : ''}}>TKGLOBAL</option>
          </select>
        </div>
      </div>
    </div>
  </form>
  {{-- <div class="row">
    <div class="col-md-12 grid-margin">
      <div class="row">
        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
          <h3 class="font-weight-bold">Welcome John</h3>
          <h6 class="font-weight-normal mb-0">All systems are running smoothly! You have <span
              class="text-primary">3 unread alerts!</span></h6>
        </div>
        <div class="col-12 col-xl-4">
          <div class="justify-content-end d-flex">
            <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
              <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button"
                id="dropdownMenuDate2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <i class="mdi mdi-calendar"></i> Today (10 Jan 2021) </button>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuDate2">
                <a class="dropdown-item" href="#">January - March</a>
                <a class="dropdown-item" href="#">March - June</a>
                <a class="dropdown-item" href="#">June - August</a>
                <a class="dropdown-item" href="#">August - November</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div> --}}
  <div class="row">
    <div class="d-none d-xl-block col-xl-6 grid-margin stretch-card">
      <div class="row">
        <div class="col-md-6 mb-4 stretch-card transparent">
          <div class="card tale-bg" style="height: 134px;">
            <div class="card-people mt-auto h-100">
              <img src="assets/images/dashboard/people.svg" alt="people" style="height: 100%; object-fit: cover;">
              <div class="weather-info">
                <div class="d-flex">
                  <div>
                    <h2 class="mb-0 font-weight-normal"><i class="mdi mdi-weather-fog me-2"></i>21<sup>°C</sup></h2>
                  </div>
                  <div class="ms-2">
                    <h4 class="location font-weight-normal">Hà Nội</h4>
                    <h6 class="font-weight-normal">Việt Nam</h6>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-4 stretch-card transparent">
          <div class="card card-tale">
            <div class="card-body">
              <p class="mb-4">Lượt click</p>
              <p class="fs-26 mb-2">{{ $clickCount }}</p>
              <p>{{ round($clickCountChange, 2) }}% ({{ $subDays + 1 }} ngày trước)</p>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 stretch-card transparent">
          <div class="card card-light-danger">
            <div class="card-body">
              <p class="mb-4">Hoa hồng Pub</p>
              <p class="fs-26 mb-2">{{ number_format($totalCom, 0, ',', '.') }}₫</p>
              <p>{{ round($totalComChange, 2) }}% ({{ $subDays + 1 }} ngày trước)</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 stretch-card transparent">
          <div class="card" style="background-color: coral; color: white;">
            <div class="card-body">
              <p class="mb-4">Hoa hồng TK</p>
              <p class="fs-26 mb-2">{{ number_format($totalComSys, 0, ',', '.') }}₫</p>
              <p>{{ round($totalComSysChange, 2) }}% ({{ $subDays + 1 }} ngày trước)</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-6 grid-margin transparent">
      <div class="row">
        <div class="col-md-6 mb-4 stretch-card transparent">
          <div class="card card-dark-blue">
            <div class="card-body">
              <p class="mb-4">Số chuyển đổi</p>
              <p class="fs-26 mb-2">{{ $totalConversion }}</p>
              <p>{{ round($totalConversionChange, 2) }}% ({{ $subDays + 1 }} ngày trước)</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-4 stretch-card transparent">
          <div class="card card-light-blue">
            <div class="card-body">
              <p class="mb-4">Giá trị chuyển đổi</p>
              <p class="fs-26 mb-2">{{ number_format($totalSales, 0, ',', '.') }}₫</p>
              <p>{{ round($totalSalesChange, 2) }}% ({{ $subDays + 1 }} ngày trước)</p>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 mb-4 mb-md-0 stretch-card transparent">
          <div class="card" style="background-color: darkgoldenrod; color: white;">
            <div class="card-body">
              <p class="mb-4">Hoa hồng tổng</p>
              <p class="fs-26 mb-2">{{ number_format($totalCom + $totalComSys, 0, ',', '.') }}₫</p>
              <p>{{ round($totalComSysChange, 2) }}% ({{ $subDays + 1 }} ngày trước)</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 stretch-card transparent">
          {{-- <div class="card card-light-danger">
            <div class="card-body">
              <p class="mb-4">Hoa hồng trong 7 ngày</p>
              <p class="fs-26 mb-2">{{ number_format($totalCom, 0, ',', '.') }}₫</p>
              <p>{{ round($totalComChange, 2) }}% ({{ $subDays + 1 }} ngày trước)</p>
            </div>
          </div> --}}
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <p class="card-title">Báo cáo tổng quan</p>
            {{-- <a href="#" class="text-info">View all</a> --}}
          </div>
          {{-- <p class="font-weight-500">The total number of sessions within the date range. It is the period time
            a user is actively engaged with your website, page or app, etc</p> --}}
          <div id="sales-chart-legend" class="chartjs-legend mb-2"></div>
          <canvas id="sales-chart"></canvas>
        </div>
      </div>
    </div>
    <div class="col-md-6 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <p class="card-title mb-0">Top Affiliates</p>
          <div class="table-responsive">
            <table class="table table-striped table-borderless">
              <thead>
                <tr>
                  <th>Email</th>
                  <th>Affiliate ID</th>
                  <th>Commission</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($topAffiliates as $user)
                <tr>
                  <td>{{ $user->email }}</td>
                  <td class="font-weight-bold">{{ $user->affiliate_id }}</td>
                  <td>{{ number_format($user->sumcom, 0, ',', '.') }}₫</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  {{-- <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card position-relative">
        <div class="card-body">
          <div id="detailedReports" class="carousel slide detailed-report-carousel position-static pt-2"
            data-bs-ride="carousel">
            <div class="carousel-inner">
              <div class="carousel-item active">
                <div class="row">
                  <div class="col-md-12 col-xl-3 d-flex flex-column justify-content-start">
                    <div class="ml-xl-4 mt-3">
                      <p class="card-title">Detailed Reports</p>
                      <h1 class="text-primary">$34040</h1>
                      <h3 class="font-weight-500 mb-xl-4 text-primary">North America</h3>
                      <p class="mb-2 mb-xl-0">The total number of sessions within the date range. It is the
                        period time a user is actively engaged with your website, page or app, etc</p>
                    </div>
                  </div>
                  <div class="col-md-12 col-xl-9">
                    <div class="row">
                      <div class="col-md-6 border-right">
                        <div class="table-responsive mb-3 mb-md-0 mt-3">
                          <table class="table table-borderless report-table">
                            <tr>
                              <td class="text-muted">Illinois</td>
                              <td class="w-100 px-0">
                                <div class="progress progress-md mx-4">
                                  <div class="progress-bar bg-primary" role="progressbar" style="width: 70%"
                                    aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                              </td>
                              <td>
                                <h5 class="font-weight-bold mb-0">713</h5>
                              </td>
                            </tr>
                            <tr>
                              <td class="text-muted">Washington</td>
                              <td class="w-100 px-0">
                                <div class="progress progress-md mx-4">
                                  <div class="progress-bar bg-warning" role="progressbar" style="width: 30%"
                                    aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                              </td>
                              <td>
                                <h5 class="font-weight-bold mb-0">583</h5>
                              </td>
                            </tr>
                            <tr>
                              <td class="text-muted">Mississippi</td>
                              <td class="w-100 px-0">
                                <div class="progress progress-md mx-4">
                                  <div class="progress-bar bg-danger" role="progressbar" style="width: 95%"
                                    aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                              </td>
                              <td>
                                <h5 class="font-weight-bold mb-0">924</h5>
                              </td>
                            </tr>
                            <tr>
                              <td class="text-muted">California</td>
                              <td class="w-100 px-0">
                                <div class="progress progress-md mx-4">
                                  <div class="progress-bar bg-info" role="progressbar" style="width: 60%"
                                    aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                              </td>
                              <td>
                                <h5 class="font-weight-bold mb-0">664</h5>
                              </td>
                            </tr>
                            <tr>
                              <td class="text-muted">Maryland</td>
                              <td class="w-100 px-0">
                                <div class="progress progress-md mx-4">
                                  <div class="progress-bar bg-primary" role="progressbar" style="width: 40%"
                                    aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                              </td>
                              <td>
                                <h5 class="font-weight-bold mb-0">560</h5>
                              </td>
                            </tr>
                            <tr>
                              <td class="text-muted">Alaska</td>
                              <td class="w-100 px-0">
                                <div class="progress progress-md mx-4">
                                  <div class="progress-bar bg-danger" role="progressbar" style="width: 75%"
                                    aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                              </td>
                              <td>
                                <h5 class="font-weight-bold mb-0">793</h5>
                              </td>
                            </tr>
                          </table>
                        </div>
                      </div>
                      <div class="col-md-6 mt-3">
                        <div class="daoughnutchart-wrapper">
                          <canvas id="north-america-chart"></canvas>
                        </div>
                        <div id="north-america-chart-legend">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="carousel-item">
                <div class="row">
                  <div class="col-md-12 col-xl-3 d-flex flex-column justify-content-start">
                    <div class="ml-xl-4 mt-3">
                      <p class="card-title">Detailed Reports</p>
                      <h1 class="text-primary">$34040</h1>
                      <h3 class="font-weight-500 mb-xl-4 text-primary">North America</h3>
                      <p class="mb-2 mb-xl-0">The total number of sessions within the date range. It is the
                        period time a user is actively engaged with your website, page or app, etc</p>
                    </div>
                  </div>
                  <div class="col-md-12 col-xl-9">
                    <div class="row">
                      <div class="col-md-6 border-right">
                        <div class="table-responsive mb-3 mb-md-0 mt-3">
                          <table class="table table-borderless report-table">
                            <tr>
                              <td class="text-muted">Illinois</td>
                              <td class="w-100 px-0">
                                <div class="progress progress-md mx-4">
                                  <div class="progress-bar bg-primary" role="progressbar" style="width: 70%"
                                    aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                              </td>
                              <td>
                                <h5 class="font-weight-bold mb-0">713</h5>
                              </td>
                            </tr>
                            <tr>
                              <td class="text-muted">Washington</td>
                              <td class="w-100 px-0">
                                <div class="progress progress-md mx-4">
                                  <div class="progress-bar bg-warning" role="progressbar" style="width: 30%"
                                    aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                              </td>
                              <td>
                                <h5 class="font-weight-bold mb-0">583</h5>
                              </td>
                            </tr>
                            <tr>
                              <td class="text-muted">Mississippi</td>
                              <td class="w-100 px-0">
                                <div class="progress progress-md mx-4">
                                  <div class="progress-bar bg-danger" role="progressbar" style="width: 95%"
                                    aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                              </td>
                              <td>
                                <h5 class="font-weight-bold mb-0">924</h5>
                              </td>
                            </tr>
                            <tr>
                              <td class="text-muted">California</td>
                              <td class="w-100 px-0">
                                <div class="progress progress-md mx-4">
                                  <div class="progress-bar bg-info" role="progressbar" style="width: 60%"
                                    aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                              </td>
                              <td>
                                <h5 class="font-weight-bold mb-0">664</h5>
                              </td>
                            </tr>
                            <tr>
                              <td class="text-muted">Maryland</td>
                              <td class="w-100 px-0">
                                <div class="progress progress-md mx-4">
                                  <div class="progress-bar bg-primary" role="progressbar" style="width: 40%"
                                    aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                              </td>
                              <td>
                                <h5 class="font-weight-bold mb-0">560</h5>
                              </td>
                            </tr>
                            <tr>
                              <td class="text-muted">Alaska</td>
                              <td class="w-100 px-0">
                                <div class="progress progress-md mx-4">
                                  <div class="progress-bar bg-danger" role="progressbar" style="width: 75%"
                                    aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                              </td>
                              <td>
                                <h5 class="font-weight-bold mb-0">793</h5>
                              </td>
                            </tr>
                          </table>
                        </div>
                      </div>
                      <div class="col-md-6 mt-3">
                        <div class="daoughnutchart-wrapper">
                          <canvas id="south-america-chart"></canvas>
                        </div>
                        <div id="south-america-chart-legend"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <a class="carousel-control-prev" href="#detailedReports" role="button" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </a>
            <a class="carousel-control-next" href="#detailedReports" role="button" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div> --}}
  {{-- <div class="row">
    <div class="col-md-7 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <p class="card-title mb-0">Top Products</p>
          <div class="table-responsive">
            <table class="table table-striped table-borderless">
              <thead>
                <tr>
                  <th>Product</th>
                  <th>Price</th>
                  <th>Date</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Search Engine Marketing</td>
                  <td class="font-weight-bold">$362</td>
                  <td>21 Sep 2018</td>
                  <td class="font-weight-medium">
                    <div class="badge badge-success">Completed</div>
                  </td>
                </tr>
                <tr>
                  <td>Search Engine Optimization</td>
                  <td class="font-weight-bold">$116</td>
                  <td>13 Jun 2018</td>
                  <td class="font-weight-medium">
                    <div class="badge badge-success">Completed</div>
                  </td>
                </tr>
                <tr>
                  <td>Display Advertising</td>
                  <td class="font-weight-bold">$551</td>
                  <td>28 Sep 2018</td>
                  <td class="font-weight-medium">
                    <div class="badge badge-warning">Pending</div>
                  </td>
                </tr>
                <tr>
                  <td>Pay Per Click Advertising</td>
                  <td class="font-weight-bold">$523</td>
                  <td>30 Jun 2018</td>
                  <td class="font-weight-medium">
                    <div class="badge badge-warning">Pending</div>
                  </td>
                </tr>
                <tr>
                  <td>E-Mail Marketing</td>
                  <td class="font-weight-bold">$781</td>
                  <td>01 Nov 2018</td>
                  <td class="font-weight-medium">
                    <div class="badge badge-danger">Cancelled</div>
                  </td>
                </tr>
                <tr>
                  <td>Referral Marketing</td>
                  <td class="font-weight-bold">$283</td>
                  <td>20 Mar 2018</td>
                  <td class="font-weight-medium">
                    <div class="badge badge-warning">Pending</div>
                  </td>
                </tr>
                <tr>
                  <td>Social media marketing</td>
                  <td class="font-weight-bold">$897</td>
                  <td>26 Oct 2018</td>
                  <td class="font-weight-medium">
                    <div class="badge badge-success">Completed</div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-5 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">To Do Lists</h4>
          <div class="list-wrapper pt-2">
            <ul class="d-flex flex-column-reverse todo-list todo-list-custom">
              <li>
                <div class="form-check form-check-flat">
                  <label class="form-check-label">
                    <input class="checkbox" type="checkbox"> Meeting with Urban Team </label>
                </div>
                <i class="remove ti-close"></i>
              </li>
              <li class="completed">
                <div class="form-check form-check-flat">
                  <label class="form-check-label">
                    <input class="checkbox" type="checkbox" checked> Duplicate a project for new customer
                  </label>
                </div>
                <i class="remove ti-close"></i>
              </li>
              <li>
                <div class="form-check form-check-flat">
                  <label class="form-check-label">
                    <input class="checkbox" type="checkbox"> Project meeting with CEO </label>
                </div>
                <i class="remove ti-close"></i>
              </li>
              <li class="completed">
                <div class="form-check form-check-flat">
                  <label class="form-check-label">
                    <input class="checkbox" type="checkbox" checked> Follow up of team zilla </label>
                </div>
                <i class="remove ti-close"></i>
              </li>
              <li>
                <div class="form-check form-check-flat">
                  <label class="form-check-label">
                    <input class="checkbox" type="checkbox"> Level up for Antony </label>
                </div>
                <i class="remove ti-close"></i>
              </li>
            </ul>
          </div>
          <div class="add-items d-flex mb-0 mt-2">
            <input type="text" class="form-control todo-list-input" placeholder="Add new task">
            <button class="add btn btn-icon text-primary todo-list-add-btn bg-transparent"><i
                class="icon-circle-plus"></i></button>
          </div>
        </div>
      </div>
    </div>
  </div> --}}
  {{-- <div class="row">
    <div class="col-md-4 stretch-card grid-margin">
      <div class="card">
        <div class="card-body">
          <p class="card-title mb-0">Projects</p>
          <div class="table-responsive">
            <table class="table table-borderless">
              <thead>
                <tr>
                  <th class="ps-0  pb-2 border-bottom">Places</th>
                  <th class="border-bottom pb-2">Orders</th>
                  <th class="border-bottom pb-2">Users</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="ps-0">Kentucky</td>
                  <td>
                    <p class="mb-0"><span class="font-weight-bold me-2">65</span>(2.15%)</p>
                  </td>
                  <td class="text-muted">65</td>
                </tr>
                <tr>
                  <td class="ps-0">Ohio</td>
                  <td>
                    <p class="mb-0"><span class="font-weight-bold me-2">54</span>(3.25%)</p>
                  </td>
                  <td class="text-muted">51</td>
                </tr>
                <tr>
                  <td class="ps-0">Nevada</td>
                  <td>
                    <p class="mb-0"><span class="font-weight-bold me-2">22</span>(2.22%)</p>
                  </td>
                  <td class="text-muted">32</td>
                </tr>
                <tr>
                  <td class="ps-0">North Carolina</td>
                  <td>
                    <p class="mb-0"><span class="font-weight-bold me-2">46</span>(3.27%)</p>
                  </td>
                  <td class="text-muted">15</td>
                </tr>
                <tr>
                  <td class="ps-0">Montana</td>
                  <td>
                    <p class="mb-0"><span class="font-weight-bold me-2">17</span>(1.25%)</p>
                  </td>
                  <td class="text-muted">25</td>
                </tr>
                <tr>
                  <td class="ps-0">Nevada</td>
                  <td>
                    <p class="mb-0"><span class="font-weight-bold me-2">52</span>(3.11%)</p>
                  </td>
                  <td class="text-muted">71</td>
                </tr>
                <tr>
                  <td class="ps-0 pb-0">Louisiana</td>
                  <td class="pb-0">
                    <p class="mb-0"><span class="font-weight-bold me-2">25</span>(1.32%)</p>
                  </td>
                  <td class="pb-0">14</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
      <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <p class="card-title">Charts</p>
              <div class="charts-data">
                <div class="mt-3">
                  <p class="mb-0">Data 1</p>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="progress progress-md flex-grow-1 me-4">
                      <div class="progress-bar bg-inf0" role="progressbar" style="width: 95%"
                        aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="mb-0">5k</p>
                  </div>
                </div>
                <div class="mt-3">
                  <p class="mb-0">Data 2</p>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="progress progress-md flex-grow-1 me-4">
                      <div class="progress-bar bg-info" role="progressbar" style="width: 35%"
                        aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="mb-0">1k</p>
                  </div>
                </div>
                <div class="mt-3">
                  <p class="mb-0">Data 3</p>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="progress progress-md flex-grow-1 me-4">
                      <div class="progress-bar bg-info" role="progressbar" style="width: 48%"
                        aria-valuenow="48" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="mb-0">992</p>
                  </div>
                </div>
                <div class="mt-3">
                  <p class="mb-0">Data 4</p>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="progress progress-md flex-grow-1 me-4">
                      <div class="progress-bar bg-info" role="progressbar" style="width: 25%"
                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="mb-0">687</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12 stretch-card grid-margin grid-margin-md-0">
          <div class="card data-icon-card-primary">
            <div class="card-body">
              <p class="card-title text-white">Number of Meetings</p>
              <div class="row">
                <div class="col-8 text-white">
                  <h3>34040</h3>
                  <p class="text-white font-weight-500 mb-0">The total number of sessions within the date
                    range.It is calculated as the sum . </p>
                </div>
                <div class="col-4 background-icon">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
      <div class="card">
        <div class="card-body">
          <p class="card-title">Notifications</p>
          <ul class="icon-data-list">
            <li>
              <div class="d-flex">
                <img src="assets/images/faces/face1.jpg" alt="user">
                <div>
                  <p class="text-info mb-1">Isabella Becker</p>
                  <p class="mb-0">Sales dashboard have been created</p>
                  <small>9:30 am</small>
                </div>
              </div>
            </li>
            <li>
              <div class="d-flex">
                <img src="assets/images/faces/face2.jpg" alt="user">
                <div>
                  <p class="text-info mb-1">Adam Warren</p>
                  <p class="mb-0">You have done a great job #TW111</p>
                  <small>10:30 am</small>
                </div>
              </div>
            </li>
            <li>
              <div class="d-flex">
                <img src="assets/images/faces/face3.jpg" alt="user">
                <div>
                  <p class="text-info mb-1">Leonard Thornton</p>
                  <p class="mb-0">Sales dashboard have been created</p>
                  <small>11:30 am</small>
                </div>
              </div>
            </li>
            <li>
              <div class="d-flex">
                <img src="assets/images/faces/face4.jpg" alt="user">
                <div>
                  <p class="text-info mb-1">George Morrison</p>
                  <p class="mb-0">Sales dashboard have been created</p>
                  <small>8:50 am</small>
                </div>
              </div>
            </li>
            <li>
              <div class="d-flex">
                <img src="assets/images/faces/face5.jpg" alt="user">
                <div>
                  <p class="text-info mb-1">Ryan Cortez</p>
                  <p class="mb-0">Herbs are fun and easy to grow.</p>
                  <small>9:00 am</small>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div> --}}
</div>
@endsection
@section('script')
<script>
$("#by_business").change(function (e) { 
  e.preventDefault();
  let url = new URL(window.location.href);
  url.searchParams.set('by_business', $(this).val());
  window.location.href = url.toString();
});
</script>
@endsection