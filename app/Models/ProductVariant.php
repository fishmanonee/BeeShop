<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
   
class ProductVariant extends Model
    {
        use HasFactory;
        protected $table = 'product_variants';
        protected $fillable = ['product_id', 'size','price','discount_price', 'stock_quantity','created_at','updated_at'];
    
        public function product()
        {
            return $this->belongsTo(Product::class, 'product_id');
        }
        public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'productvariant_id', 'id');
    }
    }
?>