<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        return view('admin.testimonials.index', ['testimonials' => Testimonial::latest()->paginate(20)]);
    }

    public function store(Request $request)
    {
        Testimonial::create($this->validateData($request));

        return back()->with('success', 'Testimoni ditambahkan.');
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $testimonial->update($this->validateData($request));

        return back()->with('success', 'Testimoni diperbarui.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return back()->with('success', 'Testimoni dihapus.');
    }

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'user_name' => ['required', 'string', 'max:100'],
            'role' => ['nullable', 'string', 'max:100'],
            'content' => ['required', 'string', 'max:1000'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => $request->boolean('is_active')];
    }
}
