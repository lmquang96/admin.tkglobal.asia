<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Conversion extends Model
{
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function click()
    {
        return $this->belongsTo(Click::class);
    }

    public static function removeDup($campaign_id, $startDate, $endDate)
    {
        $sql = "
        DELETE
        FROM conversions
        WHERE id NOT IN (
                SELECT maxId
                FROM (
                    SELECT MAX(id) maxId
                    FROM conversions
                    WHERE campaign_id = $campaign_id
                        AND order_time BETWEEN '$startDate 00:00:00'
                            AND '$endDate 23:59:59'
                    GROUP BY order_code
                        ,product_code
                    ) a
                )
            AND campaign_id = $campaign_id
            AND order_time BETWEEN '$startDate 00:00:00'
                AND '$endDate 23:59:59'
        ";

        DB::statement($sql);
    }
}
