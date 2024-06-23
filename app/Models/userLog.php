<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; // Import the base Model class

class UserLog extends Model
{
    protected $fillable = ['user_id', 'route', 'request_result'];

    /**
     * Define the fillable attributes that can be mass-assigned.
     * These attributes represent columns in the 'user_logs' table.
     */
    

    /**
     * Define a relationship with the User model.
     * Each UserLog belongs to a single User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
