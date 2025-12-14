<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    protected $primaryKey = 'OrderID';
    protected $fillable = ['CustomerID', 'OrderDate', 'Status', 'TotalAmount'];

    public function customer() {
        return $this->belongsTo(Customer::class, 'CustomerID', 'CustomerID');
    }

    public function items() {
        return $this->hasMany(OrderItem::class, 'OrderID', 'OrderID');
    }
    
    public function payment() {
        return $this->hasOne(Payment::class, 'OrderID', 'OrderID');
    }
}