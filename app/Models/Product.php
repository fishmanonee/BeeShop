<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'img',
        'description',
        'price',
        'discount_price',
        'status_id',
        'category_id',
        'type_id',
        'created_at',
        'updated_at',
        'is_hot',
    ];

    // Quan hệ với bảng types
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function totalPurchased()
    {
        return $this->hasManyThrough(OrderDetail::class, ProductVariant::class, 'product_id', 'productvariant_id', 'id', 'id')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.status_id', 5)
            ->selectRaw('SUM(order_details.quantity) as total_purchased');
    }
    
    public function fiveStarReviews()
{
    return $this->hasMany(Review::class, 'product_id')->where('rating', 5);
}

    

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'product_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'product_id');
    }
}
