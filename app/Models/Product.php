<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    protected $primaryKey = 'ProductID';
    protected $fillable = ['CategoryID', 'Name', 'Description', 'Price', 'StockQuantity', 'image'];

    public function category() {
        return $this->belongsTo(Category::class, 'CategoryID', 'CategoryID');
    }
}