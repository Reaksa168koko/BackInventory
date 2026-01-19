<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       try {
         $categories = Category::all();
         if(!$categories){
            return response()->json(
               [
                 "message" => "not fount index"
               ],404);
         }
         return response()->json([
            'message' => 'Categories retrieved successfully',
            'category' => $categories
        ], 200);
        
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
             $request->validate([
                "name" => ["required","string","min:3","max:15"]
            ]);
            $category = new Category();
            $category ->name = request("name");
            $category->save();

            return response()->json([
                'message' => 'Categories retrieved successfully',
                "category"=>$category
            ]);
        } catch (\Throwable $th) {
             return response()->json([
            "message"=>$th->getMessage()
        ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
