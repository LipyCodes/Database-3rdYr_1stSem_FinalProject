<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    // Define the custom primary key
    protected $primaryKey = 'PaymentID';

    // Allow mass assignment for these fields
    protected $fillable = [
        'OrderID',
        'PaymentDate',
        'Amount',
        'PaymentMethod',
        'PaymentStatus'
    ];

    /**
     * Relationship: A Payment belongs to one Order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'OrderID', 'OrderID');
    }
}