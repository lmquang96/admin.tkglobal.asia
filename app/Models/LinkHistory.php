<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinkHistory extends Model
{
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function click() {
        return $this->hasMany(Click::class);
    }
}
