<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\ProductTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    // public function __construct()
    // {
    //     // Apply the 'auth:sanctum' middleware to protect all actions
    //     $this->middleware('auth:sanctum');
    // }

    // Display list of products (only for authenticated users)
    public function index()
    {
        $products = Product::with('categories', 'tags', 'suppliers')->get();
        return response()->json($products, Response::HTTP_OK);
    }

    // Show a single product (available for regular users too)
    public function show($id)
    {
        $product = Product::with('categories', 'tags', 'suppliers')->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($product, Response::HTTP_OK);
    }

    // Create a new product (Admin only)
    public function store(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'nullable|url',
            'category_ids' => 'required|array',
            'tag' => 'nullable|array',
            'supplier_ids' => 'nullable|array',
            'profit_margin_type' => 'nullable|in:percentage,amount',
            'profit_margin_value' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        // Handle image upload
        $imagePath = null;
        if ($request->has('image')) {
            $imagePath = $request->image; // Store the URL as-is
        }


        // Create the product
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
            'profit_margin_type' => $request->profit_margin_type,
            'profit_margin_value' => $request->profit_margin_value,
            'final_price' => $this->calculateFinalPrice($request),
        ]);

        // Attach categories, tags, and suppliers
        $product->categories()->attach($request->category_ids);

        // Add tags if any
        if ($request->has('tag')) {
            foreach ($request->tag as $tag) {
                ProductTag::create([
                    'product_id' => $product->id,
                    'tag' => $tag,
                ]);
            }
        }

        if ($request->has('supplier_ids')) {
            $product->suppliers()->attach($request->supplier_ids);
        }

        return response()->json($product, Response::HTTP_CREATED);
    }

    // Update an existing product (Admin only)
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'nullable|url',
            'category_ids' => 'required|array',
            'tag' => 'nullable|array',
            'supplier_ids' => 'nullable|array',
            'profit_margin_type' => 'nullable|in:percentage,amount',
            'profit_margin_value' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        // Handle image upload
        $imagePath = null;
        if ($request->has('image')) {
            $imagePath = $request->image; // Store the URL as-is
        }

        // Update product details
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'profit_margin_type' => $request->profit_margin_type,
            'profit_margin_value' => $request->profit_margin_value,
            'final_price' => $this->calculateFinalPrice($request),
        ]);

        // Sync categories
        $product->categories()->sync($request->category_ids);

        // Add or update tags
        if ($request->has('tag')) {
            ProductTag::where('product_id', $product->id)->delete();
            foreach ($request->tag as $tag) {
                ProductTag::create([
                    'product_id' => $product->id,
                    'tag' => $tag,
                ]);
            }
        }

        // Sync suppliers
        if ($request->has('supplier_ids')) {
            $product->suppliers()->sync($request->supplier_ids);
        }

        return response()->json($product, Response::HTTP_OK);
    }

    // Delete a product (Admin only)
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        // Detach related categories (or other relations like suppliers, tags)
        $product->categories()->detach();  // Detach categories
        $product->suppliers()->detach();  // Detach suppliers (if applicable)
        $product->tags()->delete();       // Delete associated tags if needed

        // Delete product image if it exists
        if ($product->image) {
            Storage::delete('public/' . $product->image);
        }

        // Delete product
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], Response::HTTP_OK);
    }


    // Helper function to calculate final price based on profit margin
    private function calculateFinalPrice($request)
    {
        if ($request->profit_margin_type === 'percentage') {
            return $request->price + ($request->price * $request->profit_margin_value / 100);
        } elseif ($request->profit_margin_type === 'amount') {
            return $request->price + $request->profit_margin_value;
        }

        return $request->price;
    }
}
