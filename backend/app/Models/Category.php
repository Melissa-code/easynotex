<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * One to Many
     * Une Category peut avoir plusieurs notes
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
}
