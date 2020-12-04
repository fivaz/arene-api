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
        return response(Product::all(), Response::HTTP_OK);
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
            return response(Product::create($request->all()), Response::HTTP_CREATED);
        else
            return response(null, Response::HTTP_NOT_FOUND, ['message' => "this category_id isn't assigned to any category"]);
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
            return response($product, Response::HTTP_OK);
        else
            return response(null, Response::HTTP_NO_CONTENT);
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
