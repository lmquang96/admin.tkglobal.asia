<div class="input-group">
    <input type="text" class="form-control form-control-sm" name="{{ $name }}" id="datepicker"
      style="{{ $borderColor ? 'border-color:' . $borderColor . ';color:' . $borderColor : '' }}">
    <span class="input-group-text"
      style="padding-left: calc(0.543rem - 2px); padding-right: calc(0.543rem - 2px);{{ $borderColor ? 'border-color:' . $borderColor . ';color:' . $borderColor : '' }}">
      <i class='mdi mdi-calendar' style="font-size: 20px;"></i>
    </span>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/@easepick/datetime@1.2.1/dist/index.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@easepick/core@1.2.1/dist/index.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@easepick/base-plugin@1.2.1/dist/index.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@easepick/range-plugin@1.2.1/dist/index.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@easepick/preset-plugin@1.2.1/dist/index.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@easepick/lock-plugin@1.2.1/dist/index.umd.min.js"></script>
  <script>
    const picker = new easepick.create({
      element: document.getElementById('datepicker'),
      css: [
        'https://cdn.jsdelivr.net/npm/@easepick/core@1.2.1/dist/index.css',
        'https://cdn.jsdelivr.net/npm/@easepick/range-plugin@1.2.1/dist/index.css',
        'https://cdn.jsdelivr.net/npm/@easepick/preset-plugin@1.2.1/dist/index.css',
        'https://cdn.jsdelivr.net/npm/@easepick/lock-plugin@1.2.1/dist/index.css',
      ],
      zIndex: 999,
      lang: 'vi-VN',
      plugins: ['RangePlugin', 'PresetPlugin'],
      RangePlugin: {
        locale: {
          one: 'ngày',
          other: 'ngày',
        }
      },
      PresetPlugin: {
        customLabels: [
          'Hôm nay',
          'Hôm qua',
          '7 ngày trước',
          '30 ngày trước',
          'Tháng này',
          'Tháng trước'
        ]
      },
      setup(picker) {
        picker.on('select', (e) => {
          const {
            end,
            start,
          } = e.detail;
          let target = '{{ $name }}';
          if (target == 'chart-click-filter') {
            $.ajax({
              type: 'GET',
              url: '/click-chart',
              data: {
                sDate: moment(start).format('YYYY-MM-DD'),
                eDate: moment(end).format('YYYY-MM-DD')
              },
              success: function(response) {
                if (response.status && response.status == 200) {
                  document.querySelector('#profileReportChart').innerHTML = '';
                  let dataY = Object.values(response.data).map(num => +num);
                  let maxY = Math.max(...dataY);
                  console.log(response.data);
                  const profileReportChartEl = document.querySelector('#profileReportChart'),
                    profileReportChartConfig = {
                      series: [{
                        name: 'Lượt click',
                        data: Object.values(response.data)
                      }],
                      chart: {
                        height: 175,
                        parentHeightOffset: 0,
                        parentWidthOffset: 0,
                        toolbar: {
                          show: false
                        },
                        type: 'area'
                      },
                      dataLabels: {
                        enabled: false
                      },
                      stroke: {
                        width: 3,
                        curve: 'smooth'
                      },
                      legend: {
                        show: false
                      },
                      markers: {
                        size: 6,
                        colors: 'transparent',
                        strokeColors: 'transparent',
                        strokeWidth: 4,
                        discrete: [{
                          fillColor: config.colors.white,
                          seriesIndex: 0,
                          dataPointIndex: Object.keys(response.data).length - 1,
                          strokeColor: config.colors.warning,
                          strokeWidth: 2,
                          size: 6,
                          radius: 8
                        }],
                        hover: {
                          size: 7
                        }
                      },
                      colors: [config.colors.warning],
                      fill: {
                        type: 'gradient',
                        gradient: {
                          // shade: shadeColor,
                          shadeIntensity: 0.6,
                          opacityFrom: 0.5,
                          opacityTo: 0.25,
                          stops: [0, 95, 100]
                        }
                      },
                      grid: {
                        show: true,
                        borderColor: config.colors.borderColor,
                        strokeDashArray: 8,
                        padding: {
                          top: -20,
                          bottom: 10,
                          left: 0,
                          right: 8
                        }
                      },
                      xaxis: {
                        categories: Object.keys(response.data),
                        axisBorder: {
                          show: false
                        },
                        axisTicks: {
                          show: false
                        },
                        labels: {
                          show: true,
                          style: {
                            fontSize: '13px',
                            colors: config.colors.textMuted
                          }
                        },
                        tickAmount: 5
                      },
                      yaxis: {
                        labels: {
                          show: false,
                          style: {
                            fontSize: '13px',
                            colors: config.colors.textMuted
                          }
                        },
                        min: 0,
                        max: maxY,
                        tickAmount: 4
                      }
                    };
                  if (typeof profileReportChartEl !== undefined && profileReportChartEl !== null) {
                    const profileReportChart = new ApexCharts(profileReportChartEl,
                      profileReportChartConfig);
                    profileReportChart.render();
                  }
                }
              }
            });
          }
        });
      },
    });
  
    let date = '{{ $date ?? null }}';
    let sDate = (new Date()).setDate((new Date()).getDate() - 30);
    let eDate = new Date();
    if (date !== '') {
      let dataArray = date.split(' - ');
      sDate = new Date(dataArray[0]);
      eDate = new Date(dataArray[1]);
    }
  
    picker.setStartDate(sDate);
    picker.setEndDate(eDate);
  </script>  