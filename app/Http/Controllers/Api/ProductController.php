<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        if ($request->user()->role === 'user') {
            $products = Product::where('user_id', $request->user()->id)->get();
        } else {
            $products = Product::with('user')->where('is_active', true)->get();
        }

        return response()->json($products);
    }
    public function showAll()
    {

        $products = Product::with('user')->get();


        return response()->json($products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('products', 'public');
        }

        $product = Product::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'photo' => $photoPath,
        ]);

        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        return response()->json($product->load('user'));
    }
    public function reviews($productId)
    {
        $reviews = ProductReview::where('product_id', $productId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($reviews);
    }

    public function storeReview(Request $request, $productId)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $review = ProductReview::create([
            'product_id' => $productId,
            'name' => $validated['name'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return response()->json($review, 201);
    }

    public function update(Request $request, Product $product)
    {
        // $this->authorize('update', $product);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPath = $product->photo;
        if ($request->hasFile('photo')) {
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('products', 'public');
        }

        $product->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'photo' => $photoPath,
        ]);

        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        if ($product->photo) {
            Storage::disk('public')->delete($product->photo);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
