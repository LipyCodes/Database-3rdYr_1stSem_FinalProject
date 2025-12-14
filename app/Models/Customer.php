<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'CustomerID';
    
    // Allow password and role to be filled
    protected $fillable = [
        'FirstName', 'LastName', 'Email', 'password', 'role', 
        'Phone', 'Address', 'CreatedAt'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // Relationships remain the same
    public function orders() {
        return $this->hasMany(Order::class, 'CustomerID', 'CustomerID');
    }
}