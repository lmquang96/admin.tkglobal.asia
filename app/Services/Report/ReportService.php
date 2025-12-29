<?php

namespace App\Services\Report;

use App\Models\Conversion;
use App\Models\Click;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReportService
{
  const DEFAULT_SUB_DAYS = 7;

  public function getOrdersQueryBuilderObject($request)
  {
    try {
      if (!$request->date) {
        if (!$request->paid_at) {
          $request->merge(['date' => Carbon::now()->subDays(self::DEFAULT_SUB_DAYS)->format('Y-m-d') . " - " . Carbon::now()->format('Y-m-d')]);
        } else {
          $request->merge(['date' => Carbon::parse($request->paid_at.'-31')->subMonths(12)->format('Y-m-d') . " - " . Carbon::now()->format('Y-m-d')]);
        }
      }

      if ($request->group == 'campaign_id') {
        $request->merge(['keyword' => $request->groupValue]);
      }

      $data = Conversion::query()
        ->join('profiles', 'conversions.user_id', '=', 'profiles.user_id')
        ->join('clicks', 'clicks.id', '=', 'conversions.click_id')
        ->join('link_histories', 'link_histories.id', '=', 'clicks.link_history_id')
        ->join('campaigns', 'campaigns.id', '=', 'conversions.campaign_id')
        ->join('users', 'users.id', '=', 'conversions.user_id')
        ->when($request->date, function ($q, $date) {
          $dateArray = explode(" - ", $date);
          $q->whereBetween('order_time', [$dateArray[0] . ' 00:00:00', $dateArray[1] . ' 23:59:59']);
        })
        ->when($request->keyword, function ($q, $keyword) use ($request) {
          return $q->whereHas('campaign', function ($query) use ($keyword, $request) {
            if ($request->ex) {
                $query->where('name', '=', $keyword);
            } else {
                $query->where('name', 'like', '%' . $keyword . '%');
            }
          });
        })
        ->when($request->status, function ($q, $status) {
          // if ($request->paid_at) {
          //   $q->where('paid_at', 'like', "'".$request->paid_at."%'");
          // }
          if ($status == 'Paid') {
            $q->where('conversions.status', 'Approved')
              ->whereNotNull('paid_at');
          } else {
            $q->where('conversions.status', $status);
          }
        })
        ->when($request->by_business, function ($q, $by_business) {
          return $q->whereHas('campaign', function ($query) use ($by_business) {
            $geo = $by_business == 'TKFUNNEL' ? 'hk' : 'vn';
            $query->where('geo', $geo);
          });
        })
        ->when($request->paid_at, function ($q, $paid_at) {
          $q->where('paid_at', 'like', $paid_at . '%');
        })
        ->when($request->affiliate_id, function ($q, $affiliateId) {
          $q->where('affiliate_id', $affiliateId);
        })
        ->when($request->order_code, function ($q, $orderCode) {
          $q->where('order_code', $orderCode);
        })
        ->when($request->product_code, function ($q, $productCode) {
          $q->where('product_code', $productCode);
        })
        ->when($request->product_name, function ($q, $productName) {
          $q->where('product_name', 'like', '%' . $productName . '%');
        })
        ->when($request->sub1, function ($q, $sub1) {
          return $q->whereHas('click', function ($query) use ($sub1) {
            return $query->whereHas('linkHistory', function ($qr) use ($sub1) {
              $qr->where('sub1', 'like', '%' . $sub1 . '%');
            });
          });
        })
        ->when($request->sub2, function ($q, $sub2) {
          return $q->whereHas('click', function ($query) use ($sub2) {
            return $query->whereHas('linkHistory', function ($qr) use ($sub2) {
              $qr->where('sub2', 'like', '%' . $sub2 . '%');
            });
          });
        })
        ->when($request->sub3, function ($q, $sub3) {
          return $q->whereHas('click', function ($query) use ($sub3) {
            return $query->whereHas('linkHistory', function ($qr) use ($sub3) {
              $qr->where('sub3', 'like', '%' . $sub3 . '%');
            });
          });
        })
        ->when($request->sub4, function ($q, $sub4) {
          return $q->whereHas('click', function ($query) use ($sub4) {
            return $query->whereHas('linkHistory', function ($qr) use ($sub4) {
              $qr->where('sub4', 'like', '%' . $sub4 . '%');
            });
          });
        })
        ->select(
          'conversions.code',
          'conversions.order_time',
          'conversions.order_code',
          'conversions.product_code',
          'conversions.product_name',
          'conversions.unit_price',
          'conversions.quantity',
          'conversions.commission_pub',
          'conversions.commission_sys',
          'conversions.status',

          'clicks.created_at as click_time',

          'campaigns.name as campaign',

          'users.name as user_name',
          'users.email as user_email',

          'profiles.affiliate_id',

          'link_histories.sub1',
          'link_histories.sub2',
          'link_histories.sub3',
          'link_histories.sub4'
        );

      return $data;
    } catch (\Throwable $th) {
      Log::error('Lỗi xảy ra khi select user: ' . $th->getMessage());
    }
  }

  public function getPerformanceQueryBuilderObject($request, $group, $groupSelect)
  {
    if (!$request->date) {
      $request->merge(['date' => Carbon::now()->subDays(self::DEFAULT_SUB_DAYS)->format('Y-m-d') . " - " . Carbon::now()->format('Y-m-d')]);
    }
    $data = Conversion::with('campaign')
      ->when($request->date, function ($q, $date) {
        $dateArray = explode(" - ", $date);
        $q->whereBetween('order_time', [$dateArray[0] . ' 00:00:00', $dateArray[1] . ' 23:59:59']);
      })
      ->when($request->keyword, function ($q, $keyword) {
          return $q->whereHas('campaign', function ($query) use ($keyword) {
            $query->where('name', 'like', '%' . $keyword . '%');
          });
        // if ($group == 'campaign_id') {
        //   return $q->whereHas('campaign', function ($query) use ($keyword) {
        //     $query->where('name', 'like', '%' . $keyword . '%');
        //   });
        // } else if ($group == 'user_id') {
        //   return $q->whereHas('user', function ($query) use ($keyword) {
        //     $query->where('name', 'like', '%' . $keyword . '%');
        //   });
        // }
      })
      ->when($request->status, function ($q, $status) {
        if ($status == 'Paid') {
          $q->where('status', 'Approved')
            ->whereNotNull('paid_at');
        } else {
          $q->where('status', $status);
        }
      })
      ->when($request->by_business, function ($q, $by_business) {
        return $q->whereHas('campaign', function ($query) use ($by_business) {
          $geo = $by_business == 'TKFUNNEL' ? 'hk' : 'vn';
          $query->where('geo', $geo);
        });
      })
      ->when($request->paid_at, function ($q, $paid_at) {
        $q->where('paid_at', 'like', $paid_at . '%');
      })
      ->when($request->affiliate_id, function ($q, $affiliate_id) {
        return $q->whereHas('user.profile', function ($query) use ($affiliate_id) {
          $query->where('affiliate_id', $affiliate_id);
        });
      })
      ->selectRaw($groupSelect . ", count(*) cnt, SUM(unit_price) as total_price, SUM(commission_pub) as total_com, SUM(commission_sys) as total_com_sys")
      ->groupBy($group == 'order_time' ? 'date' : $groupSelect);
      // SUM(CASE WHEN status = 'Cancelled' THEN commission_pub ELSE 0 END) as total_com_cancel

    return $data;
  }

  public function getPerformanceClickQueryBuilderObject($request, $group, $groupSelect)
  {
    if (in_array($group, ['user_id'])) {
      $groupSelect = 'link_histories.'.$groupSelect;
    }
    $clicks = Click::query()
      ->join('link_histories', 'link_histories.id', '=', 'clicks.link_history_id')
      // ->when($group == 'user_id', function ($q) {
      //   $q->join('users', 'link_histories.user_id', '=', 'users.id');
      // })
      ->join('users', 'link_histories.user_id', '=', 'users.id')
      ->join('profiles', 'profiles.user_id', '=', 'users.id')
      ->when($request->date, function ($q, $date) {
        $dateArray = explode(" - ", $date);
        $q->whereBetween('clicks.created_at', [$dateArray[0] . ' 00:00:00', $dateArray[1] . ' 23:59:59']);
      })
      ->when($request->keyword, function ($q, $keyword) {
        return $q->join('campaigns', 'campaigns.id', '=', 'link_histories.campaign_id')
          ->where('campaigns.name', 'like', '%' . $keyword . '%');
      })
      ->when($request->by_business, function ($q, $by_business) {
        $geo = $by_business == 'TKFUNNEL' ? 'hk' : 'vn';
        return $q->join('campaigns', 'campaigns.id', '=', 'link_histories.campaign_id')
          ->where('campaigns.geo', $geo);
      })
      ->when($request->affiliate_id, function ($q, $affiliate_id) {
        return $q->where('affiliate_id', $affiliate_id);
      })
      ->where('link_histories.user_id', '!=', 8)
      ->selectRaw($groupSelect . ', count(*) cnt')
      ->groupBy($group == 'order_time' ? 'date' : $groupSelect);

    return $clicks;
  }
}
