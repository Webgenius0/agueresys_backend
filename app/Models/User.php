<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_name',
        'email',
        'phone',
        'avatar',
        'gender',
        'instagram_social_link',
        'password',
        'otp',
        'reset_password_token',
        'reset_password_token_expire_at',
        'otp_expires_at',
        'remember_token',
        'email_verified_at',
        'last_seen',
        'created_at',
        'updated_at',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'otp',
        'otp_expires_at',
        'email_verified_at',
        'reset_password_token',
        'reset_password_token_expire_at',
        'is_otp_verified',
        'created_at',
        'updated_at',
        'role',
        'status',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'otp_expires_at' => 'datetime',
            'is_otp_verified' => 'boolean',
            'reset_password_token_expires_at' => 'datetime',
            'last_seen' => 'datetime',
            'password' => 'hashed'
        ];
    }

    public function getAvatarAttribute($value): string|null
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        // Check if the request is an API request
        if (request()->is('api/*') && !empty($value)) {
            // Return the full URL for API requests
            return url($value);
        }

        // Return only the path for web requests
        return $value;
    }


    /**
     * Get the services created by the user (contractor).
     */
    public function services()
    {
        return $this->hasMany(Service::class, 'user_id');
    }

    /**
     * Get the bookings made by the user (customer).
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id');
    }


    /**
     * Get the addresses associated with the user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userAddresses()
    {
        return $this->hasMany(UserAddress::class, 'user_id');
    }


    /**
     * Get the contractor ranking associated with the user.
     */
    public function contractorRanking()
    {
        return $this->hasOne(ContractorRanking::class, 'user_id');
    }



    /**
     * Count the completed bookings for the user.
     */
    public function completedBookingsCount()
    {
        return $this->bookings()->where('status', 'completed')->count();
    }

    /**
     * Count the pending bookings for the user.
     */
    public function pendingBookingsCount()
    {
        return $this->bookings()->where('status', 'pending')->count();
    }

    /**
     * Count the reviews made by the user.
     */
    // public function reviewsCount()
    // {
    //     return $this->hasMany(Review::class, 'contactor_id')->count();
    // }
}
