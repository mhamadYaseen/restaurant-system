<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model {
    use HasFactory;

    protected $fillable = ['name', 'price', 'description', 'category_id', 'image', 'available'];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }
}