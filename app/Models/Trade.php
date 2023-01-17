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
}
