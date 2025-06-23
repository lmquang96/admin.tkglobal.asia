<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Services\Report\ReportService;
use App\Common\Transformer\Order as OrderTransformer;

class ReportOrderExport implements FromCollection, WithHeadings
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
    return [
      'ID chuyển đổi',
      'Thời gian phát sinh',
      'Thời gian click',
      'Chiến dịch',
      'Tên tài khoản',
      'Affiliate ID',
      'Email',
      'ID đơn hàng',
      'Mã sản phẩm',
      'Tên sản phẩm',
      'Giá trị đơn hàng(₫)',
      'Số lượng',
      'Hoa hồng Pub(₫)',
      'Hoa hồng TK(₫)',
      'Hoa hồng tổng(₫)',
      'Trạng thái',
      'Sub ID 1',
      'Sub ID 2',
      'Sub ID 3',
      'Sub ID 4',
    ];
  }

  public function collection()
  {
    $data = $this->reportService->getOrdersQueryBuilderObject($this->request);
    $data = $data->orderBy('order_time', 'desc')->get();

    $results = $data->map(fn($order) => OrderTransformer::exportFormat($order));

    return collect($results);
  }
}
