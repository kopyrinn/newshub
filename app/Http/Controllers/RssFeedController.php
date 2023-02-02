<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class RssFeedController extends Controller
{
    public function feed()
    {
        $posts = Post::where('status', '1')->
        orderBy('created_at', 'desc')->
        limit(50)->get();
        return response()->view('rss.feed', compact('posts'))->header('Content-Type', 'application/xml');

    }
    
    public function turbo()
    {
        $posts = Post::where('status', '1')->
        orderBy('created_at', 'desc')->
        limit(100)->get();
        
        $randoms = Post::orderByRaw('RAND()')
            ->where('status', 1)
            ->paginate(5);
            
            
        return response()->view('rss.turbo',  [
            'posts' => $posts,
            'randoms' => $randoms
    	])->header('Content-Type', 'text/xml');

    }
    
}
