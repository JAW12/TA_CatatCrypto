<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Journal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'journals';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function trades(){
        return $this->hasMany(Trade::class)->orderBy('open_time', 'desc');
    }

    public function close_trades(){
        return $this->hasMany(Trade::class)->where('status', '2')->orderBy('close_time', 'asc');
    }
}
