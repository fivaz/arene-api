<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return response(Product::with('category')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $category = Category::find($request->category_id);
        if ($category)
            return response(Product::create($request->all()), 201);
        else
            return response(null, 404, ['message' => "this category_id isn't assigned to any category"]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public
    function show(int $id)
    {
        $product = Product::find($id);
        if ($product)
            return response($product, 200);
        else
            return response(null, 204);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public
    function update(Request $request, int $id)
    {
        $product = Product::find($id);
        $product->update($request->all());
        return response($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public
    function destroy(int $id)
    {
        return response(Product::destroy($id));
    }
}
