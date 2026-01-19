<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $movements = StockMovement::with('product')->get();

            return response()->json([
                'status' => true,
                'data' => $movements
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
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
                'product_id' => 'required|exists:products,id',
                'type'       => 'required|in:in,out',
                'quantity'   => 'required|integer|min:1',
                'note'       => 'nullable|string'
            ]);

            $movement = new StockMovement();
            $movement->product_id = $data['product_id'];
            $movement->type = $data['type'];
            $movement->quantity = $data['quantity'];
            $movement->note = $data['note'] ?? null;
            $movement->save();

            return response()->json([
                'status' => true,
                'message' => 'Stock movement created successfully',
                'Stock' => $movement->load('product')
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
  public function show($id)
    {
        try {
            $movement = StockMovement::with('product')->findOrFail($id);

            return response()->json([
                'status' => true,
                'data' => $movement
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockMovement $stockMovement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $movement = StockMovement::findOrFail($id);

            $data = $request->validate([
                'product_id' => 'sometimes|exists:products,id',
                'type'       => 'sometimes|in:in,out',
                'quantity'   => 'sometimes|integer|min:1',
                'note'       => 'nullable|string'
            ]);

            $movement->update($data);

            return response()->json([
                'status' => 'update successfully',
                'data' => $movement->load('product')
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
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
            $movement = StockMovement::findOrFail($id);
            $movement->delete();

            return response()->json([
                'status' => true,
                'message' => 'Stock movement deleted successfully'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
