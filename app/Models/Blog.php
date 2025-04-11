<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'user_id',
        'image'

        // thêm các field khác nếu có
    ];

    // nếu bạn có quan hệ user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
