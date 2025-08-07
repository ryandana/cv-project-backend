<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\JsonResponse;

class BlogController extends Controller
{
    public function index(): JsonResponse
    {
        $blogs = Blog::latest()
            ->get()
            ->map(fn (Blog $b) => [
                'id'           => $b->id,
                'title'        => $b->title,
                'slug'         => $b->slug,
                'excerpt'      => $b->excerpt,
                'image_url'    => $b->image_url,
                'published_at' => $b->created_at->toDateString(),
            ]);

        return response()->json($blogs);
    }

    public function show(string $slug): JsonResponse
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();

        return response()->json([
            'id'           => $blog->id,
            'title'        => $blog->title,
            'slug'         => $blog->slug,
            'excerpt'      => $blog->excerpt,
            'content'      => $blog->content,
            'image_url'    => $blog->image_url,
            'published_at' => $blog->created_at->toDateString(),
        ]);
    }
}
