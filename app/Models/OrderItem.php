<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model {
    protected $primaryKey = 'OrderItemID';
    protected $fillable = ['OrderID', 'ProductID', 'Quantity', 'UnitPrice'];

    // This is the specific function Laravel was looking for and couldn't find
    public function product() {
        // syntax: belongsTo(Model, Foreign_Key, Owner_Key)
        return $this->belongsTo(Product::class, 'ProductID', 'ProductID');
    }

    // It is also good practice to define the relationship back to the Order
    public function order() {
        return $this->belongsTo(Order::class, 'OrderID', 'OrderID');
    }
}