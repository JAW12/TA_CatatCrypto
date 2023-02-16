<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeframeTrade extends Model
{
    use HasFactory;
    protected $table = 'timeframe_trade';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function trade(){
        return $this->belongsTo(Trade::class);
    }
}
