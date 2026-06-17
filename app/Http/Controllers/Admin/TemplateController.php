<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TemplateController extends Controller
{
    public function index()
    {
        return view('admin.templates.index', ['templates' => Template::latest()->paginate(20)]);
    }

    public function create()
    {
        return view('admin.templates.form', ['template' => new Template()]);
    }

    public function store(Request $request)
    {
        Template::create($this->validateData($request));

        return redirect()->route('admin.templates.index')->with('success', 'Template dibuat.');
    }

    public function edit(Template $template)
    {
        return view('admin.templates.form', ['template' => $template]);
    }

    public function update(Request $request, Template $template)
    {
        $template->update($this->validateData($request));

        return redirect()->route('admin.templates.index')->with('success', 'Template diperbarui.');
    }

    public function destroy(Template $template)
    {
        $template->delete();

        return redirect()->route('admin.templates.index')->with('success', 'Template dihapus.');
    }

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'type' => ['required', Rule::in(array_keys(Template::TYPES))],
            'description' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'is_premium' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]) + [
            'is_premium' => $request->boolean('is_premium'),
            'is_active' => $request->boolean('is_active'),
        ];
    }
}
