<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Services\AIBlogService;
use App\Models\Post;

Route::get('/', function () {
    return view('home');
});

Route::get('/blog', function () {
    $posts = Post::with(['category', 'tags'])
        ->published()
        ->orderBy('published_at', 'desc')
        ->paginate(12);
    
    return view('blog', compact('posts'));
});

Route::get('/drafts', function () {
    $posts = Post::with(['category', 'tags'])
        ->where('status', 'draft')
        ->orderBy('created_at', 'desc')
        ->paginate(12);
    
    return view('blog', compact('posts'));
});

// Individual post routes
Route::get('/post/{post:slug}', function (Post $post) {
    // Only show published posts or all posts if accessing directly
    return view('post', compact('post'));
});

Route::get('/draft/{post:slug}', function (Post $post) {
    // Show draft posts
    if ($post->status !== 'draft') {
        abort(404);
    }
    return view('post', compact('post'));
});

