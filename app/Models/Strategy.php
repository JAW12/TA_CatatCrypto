<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Strategy extends Model
{
    use HasFactory;

    protected $table = 'strategies';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function trades(){
        return $this->belongsToMany(Trade::class, 'strategy_trade', 'strategy_id', 'trade_id');
    }
}
