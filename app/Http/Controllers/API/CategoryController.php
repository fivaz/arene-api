<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return response(Category::all(), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);
        if ($validator->fails())
            return response($validator->messages(), Response::HTTP_BAD_REQUEST);
        else
            return response(Category::create($request->all()), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id)
    {
        $category = Category::find($id);
        if ($category)
            return response($category, Response::HTTP_OK);
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
    public function update(Request $request, int $id)
    {
        $category = Category::find($id);
        if ($category) {
            $validator = Validator::make($request->all(), [
                'name' => 'required'
            ]);
            if ($validator->fails())
                return response($validator->messages(), Response::HTTP_BAD_REQUEST);
            else {
                $category->update($request->all());
                return response(null, Response::HTTP_OK);
            }
        } else
            //TODO all these errors messages must be added to a class as constant and called whenever it's necessary to
            // avoid futur problems
            return response(['error' => "the resource you're trying to update doesn't exist"], Response::HTTP_NOT_FOUND);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id)
    {
        $category = Category::find($id);
        if ($category) {
            Category::destroy($id);
            $category->products()->update(['category_id' => null]);
            $status = Response::HTTP_OK;
            return response(null, $status);
        } else {
            $status = Response::HTTP_NOT_FOUND;
            return response(['error' => "the resource you're trying to delete doesn't exist"], $status);
        }
    }
}
