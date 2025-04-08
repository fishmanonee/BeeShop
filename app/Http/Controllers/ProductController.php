<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Type;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::withCount('wishlists')->get(); // Đếm số lượt thích
        $categories = Category::all();
        return view('admin.index2', compact('products', 'categories'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'type_id' => 'required|exists:types,id',
            'img' => 'nullable|image|max:2048',
            'size' => 'required|array',
            'stock' => 'required|array',
            'variant_price' => 'required|array',
            'variant_discount_price' => 'nullable|array'
        ]);
        $imagePath = null;
        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img'), $filename);
            $imagePath = 'img/' . $filename;
        }
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'discount_price' => $request->discount_price ?? 0,
            'description' => $request->description ?? '',
            'category_id' => $request->category_id,
            'type_id' => $request->type_id,
            'img' => $imagePath,
        ]);
        if ($product) {
            if (isset($request->stock) && is_array($request->stock)) {
                foreach ($request->size as $index => $size) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size' => $size,
                        'stock_quantity' => $request->stock[$index] ?? 0, 
                        'price' => $request->variant_price[$index],
                        'discount_price' => $request->variant_discount_price[$index] ?? null
                    ]);
                }
            }
        }
        return redirect()->back()->with('success', 'Product created successfully.');
    }


    public function create()
    {
        $categories = Category::all();
        $types = Type::all();
        return view('admin.create', compact('categories', 'types'));
    }

    public function show($id)
    {
        $product = Product::with('productVariants')->find($id);

        if (!$product) {
            abort(404, 'Product not found');
        }

        return view('admin.product.show', compact('product'));
    }


    public function getReviews($id)
    {
        $product = Product::with('reviews.user')->findOrFail($id);

        $html = "";
        foreach ($product->reviews as $review) {
            $html .= "
            <div class='border p-3 mb-3'>
                <strong>{$review->user->name}</strong> - <span class='text-warning'>⭐ {$review->rating}/5</span>
                <p>{$review->comment}</p>
                <small class='text-muted'>Ngày đánh giá: {$review->created_at->format('d/m/Y H:i')}</small>
            </div>
        ";
        }

        return response($html);
    }

    public function toggleHot(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->is_hot = $request->is_hot;
        $product->save();

        return response()->json(['success' => true]);
    }


    function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    function update(Request $request, Product $product)
    {
        $product->update($request->all());
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
