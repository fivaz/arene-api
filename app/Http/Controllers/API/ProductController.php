<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Message;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveProductRequest;
use App\Models\Category;
use App\Models\Product;
use Exception;
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
        return response(Product::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SaveProductRequest $request
     * @return Response
     */
    public function store(SaveProductRequest $request)
    {
        $product = new Product();
        $product->fill($request->validated())->save();
        return response($product, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id)
    {
        try {
            $category = Category::findOrFail($id);
            return response($category);
        } catch (Exception $exception) {
            return response(null, Response::HTTP_NO_CONTENT);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SaveProductRequest $request
     * @param int $id
     * @return Response
     */
    public function update(SaveProductRequest $request, int $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->fill($request->validated())->save();
            return response(null);
        } catch (Exception $exception) {
            return response(Message::FAILED_UPDATE, Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id)
    {
        if (Product::destroy($id))
            return response(null);
        else
            return response(Message::FAILED_DELETED, Response::HTTP_NOT_FOUND);
    }
}
