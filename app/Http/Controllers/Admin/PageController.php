<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('title_nl')->get();
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_nl'         => 'required|string|max:255',
            'title_en'         => 'nullable|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:pages,slug',
            'content_nl'       => 'nullable|string',
            'content_en'       => 'nullable|string',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_published'     => 'boolean',
        ]);

        $validated['slug'] = $validated['slug']
            ? \Illuminate\Support\Str::slug($validated['slug'])
            : Page::generateSlug($validated['title_nl']);

        $validated['is_published'] = $request->boolean('is_published');

        Page::create($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title_nl'         => 'required|string|max:255',
            'title_en'         => 'nullable|string|max:255',
            'slug'             => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'content_nl'       => 'nullable|string',
            'content_en'       => 'nullable|string',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_published'     => 'boolean',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['slug']);
        $validated['is_published'] = $request->boolean('is_published');

        $page->update($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page deleted successfully.');
    }
}
