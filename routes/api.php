<?php

use Illuminate\Support\Facades\Route;
use App\Models\Blog;

Route::get('/blogs/{slug}', function ($slug) {
    $blog = Blog::where('slug', $slug)->first();

    if (! $blog) {
        return response()->json(['message' => 'Not found'], 404);
    }

    return response()->json([
        'title'       => $blog->title,
        'slug'        => $blog->slug,
        'excerpt'     => $blog->excerpt,
        'content'     => $blog->content,
        'image_url'   => $blog->image_url, // uses accessor
        'published_at'=> $blog->created_at->format('F j, Y'),
    ]);
});
