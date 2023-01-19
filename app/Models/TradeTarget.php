<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeTarget extends Model
{
    use HasFactory;
    protected $table = 'trade_targets';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function trade(){
        return $this->belongsTo(Trade::class);
    }
}
