<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'wallets';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'id';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assets(){
        return $this->belongsToMany(Asset::class, 'asset_wallet', 'wallet_id', 'asset_id')->withPivot('amount', 'average_price', 'pnl');
    }
}
