<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Strategy extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'strategies';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function trades(){
        return $this->belongsToMany(Trade::class, 'strategy_trade', 'strategy_id', 'trade_id');
    }

    public function users(){
        return $this->belongsToMany(User::class, 'strategy_favorite', 'strategy_id', 'user_id')->withTimestamps();
    }
}
