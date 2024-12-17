<?php

namespace App\Models;

use App\Constants\Constants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Specialization extends Model
{
    use HasFactory;

    protected $table = Constants::DB_PREFIX . '_specialization_master';
    const CREATED_AT = 'date_added';
    const UPDATED_AT = 'date_modified';

    protected $fillable = [
        'specialization_name',
        'parent_id',
        'status',
        'added_by',
        'modified_by',
        'ip_address',
        'ip_modified',
    ];

    /**
     * Get the parent specialization.
     */
    public function parent()
    {
        return $this->belongsTo(Specialization::class, 'parent_id');
    }

    /**
     * Get the child specializations.
     */
    public function children()
    {
        return $this->hasMany(Specialization::class, 'parent_id');
    }

    /**
     * Scope a query to only include active specializations.
     */
    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

    /**
     * Scope a query to get only top-level specializations.
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }
}
