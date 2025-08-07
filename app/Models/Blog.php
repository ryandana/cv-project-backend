<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'image',
        'excerpt',
        'content',
    ];

    /**
     * Return full URL to the image in storage/app/public/blog-images.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        // If you stored *only* the filename, prefix with "blog-images/"
        // Otherwise, if you already stored the full path, this will pass it straight through.
        $relative = str($this->image)->startsWith('blog-images/')
            ? $this->image
            : 'blog-images/' . $this->image;

        return asset('storage/' . $relative);
    }
}
