<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Constants\Constants;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = Constants::DB_PREFIX . '_users';
    const CREATED_AT = 'date_added';
    const UPDATED_AT = 'date_modified';

    public static $ADMIN = "admin";
    public static $STAFF = "staff";
    public static $DOCTOR = "doctor";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_no',
        'password',
        'status',
        'access_group_id',
        'user_type',
        'ip_added',
        'ip_modified',
        'added_by',
        'modified_by',
        'http_user_agent'
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function clinics():HasMany{
        return $this->hasMany(ClinicDetail::class);
    }

    public function staffProfile(): HasOne{
        return $this->hasOne(StaffProfile::class);
    }

    public function doctorDetail()
    {
        return $this->hasOne(DoctorsDetail::class);  // Assuming each user has one doctor profile
    }
}
