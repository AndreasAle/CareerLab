<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiPromptTemplate;
use Illuminate\Http\Request;

class AiPromptController extends Controller
{
    public function index()
    {
        return view('admin.ai-prompts.index', ['prompts' => AiPromptTemplate::orderBy('name')->get()]);
    }

    public function edit(AiPromptTemplate $aiPrompt)
    {
        return view('admin.ai-prompts.form', ['prompt' => $aiPrompt]);
    }

    public function update(Request $request, AiPromptTemplate $aiPrompt)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'system_prompt' => ['required', 'string'],
            'user_prompt_template' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $validated['is_active'] = $request->boolean('is_active');

        $aiPrompt->update($validated);

        return redirect()->route('admin.ai-prompts.index')->with('success', 'Prompt diperbarui (tanpa deploy ulang).');
    }
}
