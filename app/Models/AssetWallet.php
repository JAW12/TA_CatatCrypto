<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetWallet extends Model
{
    use HasFactory;
    protected $table = 'asset_wallet';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function wallet(){
        return $this->belongsTo(Wallet::class);
    }

    public function asset(){
        return $this->belongsTo(Asset::class);
    }

    public function transactions(){
        return $this->hasMany(AssetTransaction::class);
    }
}
