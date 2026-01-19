<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseStatusCodeSame;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
       // GET /api/products
    public function index()
    {
        try {
            $products = Product::with(['category', 'supplier'])->get();

            return response()->json([
                'status' => true,
                'data' => $products
            ], 200);

        } catch (\Exception $e) {
           

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
  
    // POST /api/products
// POST /api/products
public function store(Request $request)
{
    try {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'sku'         => 'required|string|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'price'       => 'required|numeric|min:0',
            'quantity'    => 'required|integer|min:0',
            'description' => 'nullable|string',
            'status'      => 'required|string',
            'image'       => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $image->storeAs('products', $imageName, 'public');

            // Save image path (recommended)
            $data['image'] = 'products/' . $imageName;
        }

        // Create product
        $product = Product::create($data);

        return response()->json([
            'status'  => true,
            'message' => 'Product created successfully',
            'data'    => $product->load(['category', 'supplier']),
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'status'  => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}



    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */


public function update(Request $request, $id)
{
    try {
        $updates = Product::findOrFail($id);

        $requestd = $request->validate([
            'name'        => 'sometimes|string|max:255',
            'sku'         => 'sometimes|string|unique:products,sku,' . $updates->id,
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'price'       => 'sometimes|numeric|min:0',
            'quantity'    => 'sometimes|integer|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        // ✅ Image update
        if ($request->hasFile('image')) {

            // delete old image
            if ($updates->image && Storage::disk('public')->exists($updates->image)) {
                Storage::disk('public')->delete($updates->image);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('products', $imageName, 'public');

            $requestd['image'] = 'products/' . $imageName;
        }

        $updates->update($requestd);

        return response()->json([
            "status" => "update successfully",
            "data" => $updates
        ], 200);

    } catch (\Throwable $th) {
        return response()->json([
            "message" => $th->getMessage()
        ], 500);
    }
}


    /**
     * Remove the specified resource from storage.
     */
  public function destroy($id)
{
    try {
        $product = Product::findOrFail($id);

        // ✅ delete image
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully'
        ], 200);

    } catch (\Throwable $th) {
        return response()->json([
            'message' => $th->getMessage()
        ], 500);
    }
}
}
