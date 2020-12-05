<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Message;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
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
        try {
            $category = Category::findOrFail($id);
            return response($category, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response(null, Response::HTTP_NO_CONTENT);
        }
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
        try {
            $category = Category::findOrFail($id);
            $validator = Validator::make($request->all(), [
                'name' => 'required'
            ]);
            if ($validator->fails())
                return response($validator->messages(), Response::HTTP_BAD_REQUEST);
            else {
                $category->update($request->all());
                return response(null, Response::HTTP_OK);
            }
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
        try {
            $category = Category::findOrFail($id);
            Category::destroy($id);
            $category->products()->update(['category_id' => null]);
            return response(null, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response(Message::FAILED_DELETED, Response::HTTP_NOT_FOUND);
        }
    }
}
