<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'first_name', 'last_name', 'email', 'phone', 'address', 'city',
        'country', 'interests', 'company', 'position', 'age', 'custom_fields'
    ];

    protected $casts = [
        'custom_fields' => 'json', // Ensures custom_fields is treated as JSON
        'age' => 'integer',        // Casts age to an integer
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    // Optional: Scope for filtering (used in your controller)
    public function scopeFilter($query, array $filters)
    {
        if (!empty($filters['first_name'])) {
            $query->where('first_name', 'like', '%' . $filters['first_name'] . '%');
        }
        if (!empty($filters['last_name'])) {
            $query->where('last_name', 'like', '%' . $filters['last_name'] . '%');
        }
        if (!empty($filters['email'])) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }
        // Add more filters as needed (phone, city, etc.)
        return $query;
    }
    // Relationship
    public function emailLogs()
    {
        return $this->hasMany(EmailLog::class);
    }
}
