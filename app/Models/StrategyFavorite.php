<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrategyFavorite extends Model
{
    use HasFactory;
    protected $table = 'strategy_favorite';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
