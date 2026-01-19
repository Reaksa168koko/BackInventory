<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Exception;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
    {
        try {
            $orders = Order::with(['user', 'item.product'])->get();

            return response()->json([
                'status' => true,
                'data' => $orders
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
            $validated = $request->validate([
                'order_id'   => 'required|exists:orders,id',
                'product_id' => 'required|exists:products,id',
                'quantity'   => 'required|integer|min:1',
                'price'      => 'required|numeric|min:0',
            ]);

            $orderItem = OrderItem::create($validated);

            return response()->json([
                'status'  => true,
                'message' => 'Order item created successfully',
                'OrderItem'    => $orderItem
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to create order item',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
     public function show($id)
    {
        try {
            $order = Order::with(['user', 'items.product'])->findOrFail($id);

            return response()->json([
                'status' => true,
                'data' => $order
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
    public function edit(OrderItem $orderItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
       public function update(Request $request, $id)
    {
       try {
            $orderItem = OrderItem::findOrFail($id);

            $validated = $request->validate([
                'quantity' => 'sometimes|required|integer|min:1',
                'price'    => 'sometimes|required|numeric|min:0',
            ]);

            $orderItem->update($validated);

            return response()->json([
                'status'  => true,
                'message' => 'Order item updated successfully',
                'data'    => $orderItem
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to update order item',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
       public function destroy($id)
    {
        try {
            $orderItem = OrderItem::findOrFail($id);
            $orderItem->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Order item deleted successfully',
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to delete order item',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
