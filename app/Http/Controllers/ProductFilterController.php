<?php

namespace App\Http\Controllers;

use App\Models\ProductFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProductFilterController extends Controller
{
    
    // Get Filters
    public function index()
    {
        $filters = ProductFilter::all();
        return response()->json(['success' => true, 'data' => $filters], 200);
    }

    // Store a new filter
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'filter_name' => 'required|string|max:100',
            'filter_value' => 'required|string|max:255',
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }

        $filter = ProductFilter::create($request->all());
        return response()->json(['success' => true, 'data' => $filter], 201);
    }

    // Get a specific filter
    public function show($id)
    {
        $filter = ProductFilter::find($id);
        if (!$filter) {
            return response()->json(['success' => false, 'message' => 'Filter not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $filter], 200);
    }

    // Update a filter
    public function update(Request $request, $id)
    {
        $filter = ProductFilter::find($id);
        if (!$filter) {
            return response()->json(['success' => false, 'message' => 'Filter not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'filter_name' => 'sometimes|string|max:100',
            'filter_value' => 'sometimes|string|max:255',
            'product_id' => 'sometimes|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }

        $filter->update($request->all());
        return response()->json(['success' => true, 'data' => $filter], 200);
    }

    // Delete a filter
    public function destroy($id)
    {
        $filter = ProductFilter::find($id);
        if (!$filter) {
            return response()->json(['success' => false, 'message' => 'Filter not found'], 404);
        }

        $filter->delete();
        return response()->json(['success' => true, 'message' => 'Filter deleted successfully'], 200);
    }
}

