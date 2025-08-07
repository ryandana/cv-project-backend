<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use Illuminate\Http\JsonResponse;

class BlogController extends Controller
{
    /**
     * Return all blog posts as a resource collection.
     */
    public function index(): JsonResponse
    {
        $posts = Blog::latest()->get();
        return response()->json(BlogResource::collection($posts)->resolve());
    }

    /**
     * Return a single blog post by slug.
     */
    public function show(string $slug): BlogResource
    {
        $post = Blog::where('slug', $slug)->firstOrFail();
        return new BlogResource($post);
    }
}
