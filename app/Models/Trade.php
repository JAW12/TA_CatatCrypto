<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    use HasFactory;

    protected $table = 'trades';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function journal(){
        return $this->belongsTo(Journal::class);
    }

    public function asset(){
        return $this->belongsTo(Asset::class);
    }

    public function timeframes(){
        return $this->belongsToMany(Timeframe::class, 'timeframe_trade', 'trade_id', 'timeframe_id')->withPivot('picture_type', 'url_picture')->withTimestamps()->orderBy('timeframe_id');
    }

    public function strategies(){
        return $this->belongsToMany(Strategy::class, 'strategy_trade', 'trade_id', 'strategy_id')->withTimestamps()->withTrashed();
    }

    public function targets(){
        return $this->hasMany(TradeTarget::class, 'trade_id', 'id');
    }

    public function transactions(){
        return $this->hasMany(TradeTransaction::class, 'trade_id', 'id')->orderBy('time', 'desc');
    }
}
