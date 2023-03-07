<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gold',
        'work_finished_at',
        'job_id',
        'kingdom',
        'current_city_id',
        'task'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function reservedGold()
    {
        $reserved = 0;
        $auctions = self::auction()->get();

        if($auctions->count() > 0) {
            foreach($auctions as $auction) {
                $reserved += $auction->bid;
            }
        }

        return $reserved;
    }

    public function kingdom()
    {
        return $this->belongsTo(Kingdom::class);
    }

    public function currentCity()
    {
        return $this->belongsTo(City::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function auction()
    {
        return $this->hasMany(Auction::class, 'user_id', 'id');
    }
}
