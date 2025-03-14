<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
    public function conversion() {
        return $this->hasOne(Conversion::class);
    }

    public function linkHistory()
    {
        return $this->belongsTo(LinkHistory::class);
    }
}
