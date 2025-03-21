<?php
// app/Models/Contact.php
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
        'custom_fields' => 'array'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function emailLogs()
    {
        return $this->hasMany(EmailLog::class);
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%");
            });
        })->when($filters['age'] ?? null, function ($query, $age) {
            $query->where('age', $age);
        });
    }
}
