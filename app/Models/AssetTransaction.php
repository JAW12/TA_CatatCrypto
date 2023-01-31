<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetTransaction extends Model
{
    use HasFactory;
    protected $table = 'asset_transactions';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function asset_wallet(){
        return $this->belongsTo(AssetWallet::class);
    }
}
