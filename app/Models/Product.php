<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}