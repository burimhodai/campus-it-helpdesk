<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('tickets')->orderBy('name')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge(['slug' => Str::slug((string) $request->input('name'))]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80', 'unique:categories,name'],
            'slug' => ['required', 'string', 'max:90', 'unique:categories,slug'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        Category::create([
            ...$validated,
            'is_active' => true,
        ]);

        return back()->with('success', 'Category created.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $request->merge(['slug' => Str::slug((string) $request->input('name'))]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80', Rule::unique('categories', 'name')->ignore($category)],
            'slug' => ['required', 'string', 'max:90', Rule::unique('categories', 'slug')->ignore($category)],
            'description' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Category updated.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->tickets()->exists()) {
            return back()->withErrors(['category' => 'This category has tickets and cannot be deleted. Disable it instead.']);
        }

        $category->delete();

        return back()->with('success', 'Category deleted.');
    }
}
