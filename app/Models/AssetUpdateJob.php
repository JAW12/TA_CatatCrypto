<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetUpdateJob extends Model
{
    use HasFactory;

    protected $table = 'asset_update_jobs';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;

    public function asset(){
        return $this->belongsTo(Asset::class);
    }
}
