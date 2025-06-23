<?php

namespace App\Common\Transformer;

class Performance
{
  public static function exportFormat($item, $group, $clicks)
  {
    $groupValue = null;
    $clickByGroupId = 0;

    switch ($group) {
      case 'user_id':
        $groupValue = $item->user->profile->affiliate_id;
        $clickByGroupId = isset($clicks[$item->user_id]) ? $clicks[$item->user_id]['cnt'] : 0;
        break;

      case 'order_time':
        $groupValue = $item->date;
        $clickByGroupId = isset($clicks[$item->date]) ? $clicks[$item->date]['cnt'] : 0;
        break;
      
      default:
        $groupValue = $item->campaign->name;
        $clickByGroupId = isset($clicks[$item->campaign->id]) ? $clicks[$item->campaign->id]['cnt'] : 0;
        break;
    }

    return [
      'group' => $groupValue,
      'clickCount' => $clickByGroupId,
      'cnt' => $item->cnt,
      'total_price' => $item->total_price,
      'total_com' => $item->total_com,
      'total_com_sys' => $item->total_com_sys,
      'conversion_rate' => number_format($clickByGroupId > 0 ? ($item->cnt / $clickByGroupId) * 100 : 0, 1, ',', '.').'%'
    ];
  }
}
