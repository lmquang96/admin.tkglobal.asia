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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
  <script>
    const autoApply = {{ $autoApply }};
    const inputName = '{{ $name }}';
    const subDays = inputName == 'dashboard_date' ? 6 : 29;
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
      autoApply: autoApply == "1" ? true : false,
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
          if (target == 'dashboard_date') {
            window.location.href = "{{ route('dashboard') }}" + "?date=" + moment(start).format('YYYY-MM-DD') + " - " + moment(end).format('YYYY-MM-DD');
          }
        });
      },
    });
  
    let date = '{{ $date ?? null }}';
    let sDate = (new Date()).setDate((new Date()).getDate() - subDays);
    let eDate = new Date();
    if (date !== '') {
      let dataArray = date.split(' - ');
      sDate = new Date(dataArray[0]);
      eDate = new Date(dataArray[1]);
    }
  
    picker.setStartDate(sDate);
    picker.setEndDate(eDate);
  </script>  