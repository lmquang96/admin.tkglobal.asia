<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Services\Report\ReportService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Common\Transformer\Performance;

class ReportPerformanceExport implements FromCollection, WithHeadings
{
  /**
   * @return \Illuminate\Support\Collection
   */

  protected $request;
  protected $reportService;

  public function __construct(Request $request, ReportService $reportService)
  {
    $this->request = $request;
    $this->reportService = $reportService;
  }

  public function headings(): array
  {
    $groupName = $this->request->group == 'user_id' ? 'Tài khoản' : ($this->request->group == 'order_time' ? 'Thời gian' : 'Chiến dịch');

    return [
      $groupName,
      'Lượt click',
      'Chuyển đổi',
      'Giá trị chuyển đổi',
      'Hoa hồng Pub(₫)',
      'Hoa hồng TK(₫)',
      'Tỉ lệ',
    ];
  }

  public function collection()
  {
    $group = $this->request->group;
    if (empty($group)) {
      $group = 'campaign_id';
    }
    $groupSelect = '';
    if ($group == 'campaign_id') {
      $groupSelect = 'campaign_id';
    } else if ($group == 'user_id') {
      $groupSelect = 'user_id';
    } else if ($group == 'order_time') {
      $groupSelect = "date_format(order_time, '%Y-%m-%d') date";
    }

    $data = $this->reportService->getPerformanceQueryBuilderObject($this->request, $group, $groupSelect);

    $data = $data->get();

    if ($group == 'order_time') {
      $groupSelect = "date_format(clicks.created_at, '%Y-%m-%d') date";
    }
    
    $clicks = $this->reportService->getPerformanceClickQueryBuilderObject($this->request, $group, $groupSelect);

    $clicks = $clicks->get();

    $clicks = $clicks->keyBy($group == 'order_time' ? 'date' : $group)->toArray();

    $results = $data->map(fn($item) => Performance::exportFormat($item, $group, $clicks));

    return collect($results);
  }
}
