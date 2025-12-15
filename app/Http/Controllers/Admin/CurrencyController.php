<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::orderBy('id')->get();
        return view('admin.settings.currencies.index', compact('currencies'));
    }

    public function create()
    {
        return view('admin.settings.currencies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:currencies,name',
        ]);

        Currency::create(['name' => $request->name]);

        return redirect()->route('admin.settings.currencies.index')
            ->with('success', 'Currency created successfully');
    }

    public function edit(Currency $currency)
    {
        return view('admin.settings.currencies.edit', compact('currency'));
    }

    public function update(Request $request, Currency $currency)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:currencies,name,' . $currency->id,
        ]);

        $currency->update(['name' => $request->name]);

        return redirect()->route('admin.settings.currencies.index')
            ->with('success', 'Currency updated successfully');
    }

    public function destroy(Currency $currency)
    {
        $currency->delete();

        return redirect()->route('admin.settings.currencies.index')
            ->with('success', 'Currency deleted successfully');
    }
}