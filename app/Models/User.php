<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable; // Import the Authenticatable contract
use Illuminate\Database\Eloquent\Factories\HasFactory; // Import the HasFactory trait
use Illuminate\Database\Eloquent\Model; // Import the base Model class
use Illuminate\Auth\Authenticatable as AuthenticatableTrait; // Import the AuthenticatableTrait for Eloquent

class User extends Model implements Authenticatable
{
    use HasFactory, AuthenticatableTrait; // Use the HasFactory and AuthenticatableTrait traits

    // Define the fillable attributes that can be mass-assigned
    protected $fillable = [
        'name', 'email', 'password',
    ];
}

