<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Snippet extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'category_id',
        'user_id',
        'views',
        'password',
        'burn_after_read',
        'expiration_time',
        'unique_id',
        'is_public',
        'created_at',
    ];

    protected $attributes = [
        'views' => 0,
        'burn_after_read' => false,
        'is_public' => true,
        'password' => null,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
