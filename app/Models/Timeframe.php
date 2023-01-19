<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timeframe extends Model
{
    use HasFactory;

    protected $table = 'timeframes';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function trades(){
        return $this->belongsToMany(Trade::class, 'timeframe_trade', 'timeframe_id', 'trade_id');
    }
}
