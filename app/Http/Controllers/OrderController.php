<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            $order = Order::with(["user","item"])->get();
            if(!$order){
                return response()->json([
                    "message" => "Not found",
                ],404);
            }
            return response()->json([
                "status "=>true,
                "data" => $order
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                "message"=>$th->getMessage()
            ],500);
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
            'user_id'      => 'required|exists:users,id',
            'order_date'   => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'status'       => 'required|string'
        ]);

        $order = new Order();
        $order->user_id = $data['user_id'];
        $order->order_date = $data['order_date'];
        $order->total_amount = $data['total_amount'];
        $order->status = $data['status'];
        $order->save();

        return response()->json([
            'status' => true,
            'message' => 'Order created successfully',
            'Order' => $order
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
            $order = Order::with(['user', 'item'])->findOrFail($id);

            return response()->json([
                'status' => true,
                'Show_Order' => $order
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
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);

            $data = $request->validate([
                'user_id'      => 'sometimes|exists:users,id',
                'order_date'   => 'sometimes|date',
                'total_amount' => 'sometimes|numeric|min:0',
                'status'       => 'sometimes|string'
            ]);

            $order->update($data);

            return response()->json([
                'status' => 'update successfully',
                'Updte_order        ' => $order
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
            $order = Order::findOrFail($id);
            $order->delete();

            return response()->json([
                'status' => true,
                'message' => 'Order deleted successfully'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
