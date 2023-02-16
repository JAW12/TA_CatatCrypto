<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $table = 'assets';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function wallets(){
        return $this->belongsToMany(Wallet::class, 'asset_wallet', 'asset_id', 'wallet_id');
    }

    public function users(){
        return $this->belongsToMany(User::class, 'asset_watchlist', 'asset_id', 'user_id')->withTimestamps();
    }

    public function trades(){
        return $this->belongsToMany(Trade::class, 'trades', 'asset_id', 'id');
    }

}
