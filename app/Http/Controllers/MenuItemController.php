<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function index()
    {
        return response()->json(MenuItem::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|string'
        ]);

        if (isset($data['image'])) {
            $data['image'] = str_replace(url('/'), '', $data['image']);
            $data['image'] = ltrim($data['image'], '/');
        }

        $item = MenuItem::create($data);
        return response()->json(['success' => true, 'item' => $item]);
    }

    public function update(Request $request, $id)
    {
        $item = MenuItem::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|string'
        ]);

        if (isset($data['image'])) {
            $data['image'] = str_replace(url('/'), '', $data['image']);
            $data['image'] = ltrim($data['image'], '/');
        }

        $item->update($data);
        return response()->json(['success' => true, 'item' => $item]);
    }

    public function destroy($id)
    {
        $item = MenuItem::findOrFail($id);
        $item->delete();
        return response()->json(['success' => true]);
    }
}
