<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index(Request $request)
    {
        // Fetch foods with pagination (no sorting/filtering for the older layout)
        $foods = Food::paginate(10);

        return view('admin.order_categories', compact('foods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'category.required' => 'Please enter a category for the food item.',
        ]);

        $foodData = $request->only(['name', 'description', 'price', 'category']);
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('food_images', 'public');
            $foodData['image'] = $path;
        }

        Food::create($foodData);

        return redirect()->route('admin.order_categories')->with('success', 'Food item added successfully!');
    }

    public function edit(Food $food)
    {
        return view('admin.food_edit', compact('food'));
    }

    public function update(Request $request, Food $food)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'category.required' => 'Please enter a category for the food item.',
        ]);

        $foodData = $request->only(['name', 'description', 'price', 'category']);
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('food_images', 'public');
            $foodData['image'] = $path;
        }

        $food->update($foodData);

        return redirect()->route('admin.order_categories')->with('success', 'Food item updated successfully!');
    }

    public function destroy(Food $food)
    {
        $food->delete();
        return redirect()->route('admin.order_categories')->with('success', 'Food item deleted successfully!');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $foods = Food::where('name', 'like', "%{$query}%")->get();
        return response()->json($foods);
    }
}