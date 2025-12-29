<?php

namespace App\Common\Transformer;

class Order
{
  public static function exportFormat($item)
  {
    return [
      'code' => $item->code,
      'order_time' => $item->order_time,
      'click_time' => $item->click_time,
      'campaign' => $item->campaign,
      'user_name' => $item->user_name,
      'affiliate_id' => $item->affiliate_id,
      'user_email' => $item->user_email,
      'order_code' => $item->order_code,
      'product_code' => $item->product_code,
      'product_name' => $item->product_name,
      'unit_price' => $item->unit_price,
      'quantity' => $item->quantity,
      'commission_pub' => $item->commission_pub,
      'commission_sys' => $item->commission_sys,
      'commission_all' => $item->commission_pub + $item->commission_sys,
      'status' => $item->status,
      'sub1' => $item->sub1,
      'sub2' => $item->sub2,
      'sub3' => $item->sub3,
      'sub4' => $item->sub4,
    ];
  }
}
