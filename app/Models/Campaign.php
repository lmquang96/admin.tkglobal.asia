<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function conversions() {
        return $this->hasMany(Conversion::class);
    }

    public function linkHistories() {
        return $this->hasMany(LinkHistory::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }
}
