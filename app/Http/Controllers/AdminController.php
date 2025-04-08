<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Role;
use App\Models\Type;

class AdminController extends Controller
{
    // Show sản phẩm
    public function index2()
    {
        return view('admin.index2');
    }

    public function productlist()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $products = Product::orderBy('id', 'DESC')->paginate(20);
        return view('admin.productlist', compact('categories', 'products'));
    }

    // Edit sản phẩm
    public function editProduct($id)
    {
        $product = Product::with('productVariants')->find($id);
        $categories = Category::all();
        $types = Type::all();
        return view('admin.edit_product', compact('product', 'categories', 'types'));
    }
    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'discount_price' => 'nullable|numeric',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'type_id' => 'required|exists:types,id',
            'img' => 'nullable|image|max:2048',
        ]);
        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->price = $request->price;
        $product->discount_price = $request->discount_price;
        $product->description = $request->description;
        $product->category_id = $request->category_id;
        if ($request->hasFile('img')) {
            // Xóa ảnh cũ nếu có
            if ($product->img && file_exists(public_path($product->img))) {
                unlink(public_path($product->img));
            }
            // Lưu ảnh mới vào public/img/
            $imageName = time() . '.' . $request->file('img')->getClientOriginalExtension();
            $request->file('img')->move(public_path('img'), $imageName);
            $product->img = 'img/' . $imageName; // Lưu đường dẫn ảnh
        }
        $product->productVariants()->delete();
        for ($i = 0; $i < count($request->size); $i++) {
            $product->productVariants()->create([
                'size' => $request->size[$i],
                'stock_quantity' => $request->stock_quantity[$i],
                'price' => $request->variant_price[$i],
                'discount_price' => $request->variant_discount_price[$i] ?? null,
            ]);
        }
        $product->save();
        return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }



    public function userlist()
    {
        $roles = Role::orderBy('name', 'ASC')->get();

        $users = User::orderBy('id', 'DESC')->paginate(10);
        return view('admin.user.index', compact('users', 'roles'));
    }


    public function categorylist()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('admin.categorylist', compact('categories'));
    }

    public function productadd(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|integer|exists:categories,id',
            'img' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('img')) {
            $imageName = time() . '.' . $request->img->extension();
            $request->img->move(public_path('img'), $imageName);
            $validatedData['img'] = $imageName;
        }

        Product::create($validatedData);
        return redirect()->route('admin2');
    }

    public function createCategory()
    {
        return view('admin.category.create');
    }

    function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'img' => 'nullable|image|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img'), $filename);
            $imagePath = 'img/' . $filename;
        }

        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'discount_price' => $request->discount_price ?? 0,
            'description' => $request->description ?? '',
            'category_id' => $request->category_id,
            'img' => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Product created successfully.');
    }



    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.category.create')->with('success', 'Category added successfully!');
    }

    public function editCategory($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return redirect()->route('admin.category.index')->with('error', 'Category not found!');
        }

        return view('admin.category.edit', compact('category'));
    }

    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $category = Category::find($id);
        if (!$category) {
            return redirect()->route('categories.index')->with('error', 'Category not found!');
        }

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }


    public function destroyCategory($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        $category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully.');
    }


    public function categoryadd(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create($validatedData);
        return redirect()->route('categorylist');
    }

    public function destroyProduct($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }



        $product->delete();
        return redirect()->back()->with('success', 'Product deleted successfully.');
    }

    public function productupdateform($id)
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $products = Product::orderBy('id', 'DESC')->paginate(20);
        $product = Product::find($id);
        return view('admin.productupdateform', compact('categories', 'products', 'product'));
    }

    public function productupdate(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|integer|exists:categories,id',
            'img' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
            'quantity' => 'nullable|numeric',
        ]);

        $product = Product::findOrFail($request->id);

        if ($request->hasFile('img')) {
            $imageName = time() . '.' . $request->img->extension();
            $request->img->move(public_path('uploaded'), $imageName);
            $validatedData['img'] = $imageName;

            $oldImagePath = public_path('uploaded/' . $product->img);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        $product->update($validatedData);
        return redirect()->route('productlist');
    }
}
