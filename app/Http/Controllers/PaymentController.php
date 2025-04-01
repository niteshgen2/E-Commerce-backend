<?php

// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'amount' => 'required|numeric|min:0',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Find the product
        $product = Product::find($validated['product_id']);

        // Check if the product exists
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Process the payment logic here (e.g., deducting amount, payment gateway integration)

        // Create a new payment record in the database
        $payment = Payment::create([
            'user_id' => $user->id,
            'product_id' => $validated['product_id'],
            'amount' => $validated['amount'],
        ]);

        // Create a custom response where the id appears first
        $response = [
            'id' => $payment->id,  // Make sure 'id' is the first field
            'user_id' => $payment->user_id,
            'product_id' => $payment->product_id,
            'amount' => $payment->amount,
            'created_at' => $payment->created_at,
            'updated_at' => $payment->updated_at
        ];

        // Return a success response with the custom formatted payment data
        return response()->json([
            'message' => 'Payment processed successfully',
            'payment' => $response
        ], 201);
    }
}
