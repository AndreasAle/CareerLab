<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        return view('admin.blog.index', ['posts' => BlogPost::latest()->paginate(20)]);
    }

    public function create()
    {
        return view('admin.blog.form', ['post' => new BlogPost(['status' => 'draft'])]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(4);
        $data['published_at'] = $data['status'] === 'published' ? now() : null;
        BlogPost::create($data);

        return redirect()->route('admin.blog.index')->with('success', 'Artikel dibuat.');
    }

    public function edit(BlogPost $blog)
    {
        return view('admin.blog.form', ['post' => $blog]);
    }

    public function update(Request $request, BlogPost $blog)
    {
        $data = $this->validateData($request);
        if ($data['status'] === 'published' && ! $blog->published_at) {
            $data['published_at'] = now();
        }
        $blog->update($data);

        return redirect()->route('admin.blog.index')->with('success', 'Artikel diperbarui.');
    }

    public function destroy(BlogPost $blog)
    {
        $blog->delete();

        return redirect()->route('admin.blog.index')->with('success', 'Artikel dihapus.');
    }

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'excerpt' => ['nullable', 'string', 'max:300'],
            'content' => ['required', 'string'],
            'status' => ['required', 'in:draft,published'],
        ]);
    }
}
