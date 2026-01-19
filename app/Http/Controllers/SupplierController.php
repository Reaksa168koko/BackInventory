<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
    {
        try {
            $suppliers = Supplier::all();
            return response()->json([
                'status' => true,
                'Supplier' => $suppliers
            ]);
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
     public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name'    => 'required|string|max:255',
                'phone'   => 'nullable|string|max:20',
                'email'   => 'nullable|email|max:255',
                'address' => 'nullable|string',
            ]);

            $supplier = new Supplier();
            $supplier->name = $data['name'];
            $supplier->phone = $data['phone'] ?? null;
            $supplier->email = $data['email'] ?? null;
            $supplier->address = $data['address'] ?? null;
            $supplier->save();

            return response()->json([
                'status' => true,
                'message' => 'Supplier created successfully',
                'data' => $supplier
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        try {
         $supplier = Supplier::findOrFail($id);

        // Validate request data
         $validatedData = $request->validate([
            'name'    => 'sometimes|string|max:255',
            'phone'   => 'sometimes|string|max:20',
            'email'   => 'sometimes|email|max:255',
            'address' => 'nullable|string',
        ]);

        // Update supplier
        $supplier->update($validatedData);

        return response()->json([
            'status'  => true,
            'message' => 'Updated successfully',
            'data'    => $supplier
        ], 200);
        } catch (\Throwable $th) {
             return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $deleted = Supplier::find($id);
            if(!$deleted){
                return response()->json([
                    "status"=>false,
                    "message"=>"Supplier not found"
                ],500);
            }
            $deleted->delete($deleted);
            return response()->json([
                "status"=>true,
                "message"=>"delete successfully"
            ],200);
        } catch (\Throwable $th) {
             return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
