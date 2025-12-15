<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index()
    {
        if (!auth()->user()->canManageUsers()) {
            abort(403, 'Unauthorized action.');
        }
        
        $sliders = Slider::orderBy('order')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        if (!auth()->user()->canManageUsers()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->canManageUsers()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'order' => 'required|integer',
        ]);
    
        // Handle checkbox - if not checked, it won't be in request
        $validated['active'] = $request->has('active') ? 1 : 0;
    
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            
            $uploadPath = public_path('portal/img/slider');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $filename);
            $validated['image'] = 'img/slider/' . $filename;
        }
    
        Slider::create($validated);
    
        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider successfully added');
    }
    
    public function update(Request $request, Slider $slider)
    {
        if (!auth()->user()->canManageUsers()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'order' => 'required|integer',
        ]);
    
        // Handle checkbox
        $validated['active'] = $request->has('active') ? 1 : 0;
    
        if ($request->hasFile('image')) {
            if ($slider->image && file_exists(public_path('portal/' . $slider->image))) {
                unlink(public_path('portal/' . $slider->image));
            }
            
            $file = $request->file('image');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            
            $uploadPath = public_path('portal/img/slider');
            $file->move($uploadPath, $filename);
            $validated['image'] = 'img/slider/' . $filename;
        }
    
        $slider->update($validated);
    
        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider successfully updated');
    }

    public function edit(Slider $slider)
    {
        if (!auth()->user()->canManageUsers()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('admin.sliders.edit', compact('slider'));
    }

    public function destroy(Slider $slider)
    {
        if (!auth()->user()->canManageUsers()) {
            abort(403, 'Unauthorized action.');
        }
        
        if ($slider->image && file_exists(public_path('portal/' . $slider->image))) {
            unlink(public_path('portal/' . $slider->image));
        }
        
        $slider->delete();

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider successfully deleted');
    }
}