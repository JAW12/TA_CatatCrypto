<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'password',
        'user_type',
        'status',
        'gender',
        'birthdate',
        'max_wallets',
        'max_journals',
        'trades_quantity_per_month',
        'remaining_trades',
        'membership_since',
        'membership_till',
        'spent'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = ['email_verified_at'];

    protected $appends = ['full_name'];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function wallets(){
        return $this->hasMany(Wallet::class)->withTrashed();
    }

    public function active_wallets(){
        return $this->hasMany(Wallet::class);
    }

    public function journals(){
        return $this->hasMany(Journal::class)->withTrashed();
    }

    public function transactions(){
        return $this->hasMany(Transaction::class, 'user_id', 'id')->orderBy('created_at', 'desc');
    }

    public function memberships(){
        return $this->belongsToMany(Membership::class, 'membership_user', 'user_id', 'membership_id')->withPivot('membership_expiration', 'status')->withTimestamps()->orderBy('membership_expiration', 'desc');
    }

    public function membership(){
        return $this->belongsToMany(Membership::class, 'membership_user', 'user_id', 'membership_id')->withPivot('membership_expiration', 'status')->withTimestamps()->wherePivot('status', 1);
    }

    public function watchlist(){
        return $this->belongsToMany(Asset::class, 'asset_watchlist', 'user_id', 'asset_id')->withTimestamps();
    }

    public function favorite(){
        return $this->belongsToMany(Strategy::class, 'strategy_favorite', 'user_id', 'strategy_id')->withTimestamps();
    }
}
