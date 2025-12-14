<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    protected $primaryKey = 'CategoryID';
    protected $fillable = ['CategoryName', 'Description'];

    // Relationship: A Category has many Products [cite: 29]
    public function products() {
        return $this->hasMany(Product::class, 'CategoryID', 'CategoryID');
    }
}