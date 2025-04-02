<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Create a new product
    public function create(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'sku' => 'required|string|unique:products',
            'name' => 'required|string|max:255',
            'detail' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Image upload validation
        ]);

        // Handle image upload (if provided)
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/images');
            $validated['image'] = basename($imagePath); // Save the image filename (not the full path)
        }

        // Create the product in the database
        $product = Product::create($validated);

        // Return a success response with the created product
        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }
    public function destroy($id)
{
    $product = Product::find($id);

    if (!$product) {
        return response()->json(['message' => 'Product not found'], 404);
    }

    $product->delete();

    return response()->json(['message' => 'Product deleted successfully']);
}
}

