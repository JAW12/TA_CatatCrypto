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

    public function wallet(){
        return $this->belongsToMany(Wallet::class, 'asset_wallet', 'asset_id', 'wallet_id');
    }
}
