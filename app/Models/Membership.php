<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;

    protected $table = 'memberships';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function transactions(){
        return $this->hasMany(Transaction::class, 'membership_id', 'id');
    }

    public function users(){
        return $this->belongsToMany(User::class, 'membership_user', 'membership_id', 'user_id');
    }
}
