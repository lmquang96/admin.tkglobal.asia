<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Services\Report\ReportService;
use App\Common\Transformer\Order as OrderTransformer;

class ReportOrderExport implements FromQuery, WithHeadings, WithChunkReading, WithMapping
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

  // public function collection()
  // {
  //   $data = $this->reportService->getOrdersQueryBuilderObject($this->request);
  //   $data = $data->orderBy('order_time', 'desc')->get();

  //   $results = $data->map(fn($order) => OrderTransformer::exportFormat($order));

  //   return collect($results);
  // }

  public function query()
  {
    return app(ReportService::class)
      ->getOrdersQueryBuilderObject($this->request)
      ->orderBy('order_time', 'desc');
  }

  public function map($order): array
  {
    return OrderTransformer::exportFormat($order);
  }

  public function chunkSize(): int
  {
    return 1000;
  }
}
