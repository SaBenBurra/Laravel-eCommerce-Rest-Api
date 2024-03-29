<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return response(CategoryResource::collection(Category::all()),200);
        } catch (\Exception $e) {
            return response(config('responses.as_array.error'),500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        try {
            $category = new CategoryResource(Category::create($data));
            return response($category, 200);
        } catch (\Exception $e) {
            return response(config('responses.as_array.error'), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);
        } catch (\Exception $e) {
            return response(config('responses.as_array.error'), 500);
        }
        return response(new CategoryResource($category), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $data = $request->validated();

        try {
            $category = Category::findOrFail($id);
        } catch(Exception $e) {
            return response(config('responses.as_array.not_found'), 404);
        }
        try {
            $category->update($data);
            return response(new CategoryResource($category), 200);
        } catch (\Exception $e) {
            return response(config('responses.as_array.error'), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(auth('sanctum')->user()->cannot('delete', Category::class)) {
            return response(config('responses.as_array.unauthorized'), 403);
        }

        try {
            $category = Category::findOrFail($id);
        } catch (\Exception $e) {
            return response(config('responses.as_array.not_found'), 404);
        }

        $deleteCount = Category::destroy($id);

        return response(["deleted" => $deleteCount], 200);
    }
}
