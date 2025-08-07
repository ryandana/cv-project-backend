<?php

use Illuminate\Support\Facades\Route;
use App\Models\Blog;

Route::get('/blog/{slug}', function ($slug) {
    $blog = Blog::where('slug', $slug)->first();

    if (! $blog) {
        abort(404, 'Blog not found');
    }

    return view('blog.show', compact('blog'));
});
