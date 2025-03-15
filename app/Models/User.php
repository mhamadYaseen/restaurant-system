<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
    
    /**
     * Check if the user has any specified role
     *
     * @return bool
     */
    public function hasRole(): bool
    {
        return !is_null($this->role) && $this->role !== 'pending';
    }
    
    /**
     * Check if the user is an admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    
    /**
     * Check if the user is a regular user (not admin, not pending)
     *
     * @return bool
     */
    public function isRegularUser(): bool
    {
        return $this->role === 'user';
    }
    
    /**
     * Check if the user is pending approval
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->role === 'pending' || is_null($this->role);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}