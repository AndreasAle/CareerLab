<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Plan;
use App\Models\Testimonial;

class LandingController extends Controller
{
    public function index()
    {
        return view('public.landing', [
            'plans' => Plan::where('is_active', true)->orderBy('price')->get(),
            'testimonials' => Testimonial::where('is_active', true)->latest()->take(4)->get(),
        ]);
    }

    public function pricing()
    {
        return view('public.pricing', [
            'plans' => Plan::where('is_active', true)->orderBy('price')->get(),
        ]);
    }

    public function blogIndex()
    {
        return view('public.blog-index', [
            'posts' => BlogPost::published()->latest('published_at')->paginate(9),
        ]);
    }

    public function blogShow(BlogPost $post)
    {
        abort_unless($post->status === 'published', 404);

        return view('public.blog-show', ['post' => $post]);
    }
}
