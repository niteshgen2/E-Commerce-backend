<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Place an order
    public function placeOrder(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'product_ids' => 'required|array',  // List of product IDs to order
            'product_ids.*' => 'exists:products,id', // Ensure each product exists in the database
            'total_amount' => 'required|numeric|min:0',
            'order_status' => 'required|in:pending,completed,cancelled',
            'payment_status' => 'required|in:pending,paid,failed',
            'shipping_status' => 'required|in:pending,shipped,delivered',
            'placed_at' => 'nullable|date', // If placed_at is passed, it must be a valid date
        ]);
     
      

        // Get the authenticated user
        $user = Auth::user();

        // Calculate the total amount based on product prices (you can remove this part if total_amount is already accurate)
        $totalAmount = 0;
        foreach ($validated['product_ids'] as $productId) {
            $product = Product::find($productId);
            $totalAmount += $product->price;  // Add the price of each product to the total
        }

        // If the total amount doesn't match the provided total_amount, throw an error
        if ($validated['total_amount'] != $totalAmount) {
            return response()->json(['message' => 'The total amount does not match the sum of product prices.'], 400);
        }

        // Create the order with the correct fields (id, user_id, total_amount, order_status, payment_status, shipping_status, placed_at)
        $order = Order::create([
            'user_id' => $user->id,  // Set the user ID of the authenticated user
            'total_amount' => $validated['total_amount'],
            'order_status' => $validated['order_status'],
            'payment_status' => $validated['payment_status'],
            'shipping_status' => $validated['shipping_status'],
            'placed_at' => $validated['placed_at'] ?? now(),  // Use the provided placed_at or set the current time
        ]);

        // Optionally: Associate the products with the order (assuming you want to track ordered products)
        foreach ($validated['product_ids'] as $productId) {
            $product = Product::find($productId);
            $order->products()->attach($product);  // Assuming a many-to-many relationship with products
        }

        // Return a success response
        return response()->json([
            'message' => 'Order placed successfully',
            // 'order' => $order // Make sure $order is a valid object
        ], 201);
        
    }
}
