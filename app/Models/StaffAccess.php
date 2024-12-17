<?php

namespace App\Models;

use App\Constants\Constants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffAccess extends Model
{
    use HasFactory;

    protected $table = Constants::DB_PREFIX . '_staff_access';
    // protected $table = 'staff_access';


    protected $fillable = ['staff_profile_id', 'categories', 'view', 'add', 'edit', 'delete'] ;

    // Define relationships
    public function staffProfile()
    {
        return $this->belongsTo(StaffProfile::class);
    }
}
