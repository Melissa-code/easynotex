<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'category_id', 'user_id'];

    /**
     * One to Many
     * Une Note appartient à une Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * One to Many
     * Une Note appartient à un User 
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
