<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductVariantStoreRequest;
use App\Http\Requests\ProductVariantUpdateRequest;
use App\Http\Resources\ProductVariantResource;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{

    public function __construct()
    {
        $this->middleware('check_admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(ProductVariantResource::collection(ProductVariant::all()), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductVariantStoreRequest $request)
    {
        $data = $request->validated();

        try {
            $productVariant = ProductVariant::create($data);
        } catch (\Exception $e) {
            return response(config('responses.as_array.error'), 500);
        }

        return response(new ProductVariantResource($productVariant), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $productVariant = ProductVariant::findOrFail($id);
        } catch (\Exception $e) {
            return response(config('responses.as_array.not_found'), 404);
        }

        return response(new ProductVariant($productVariant), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductVariantUpdateRequest $request, $id)
    {
        try {
            $productVariant = ProductVariant::findOrFail($id);
        } catch (\Exception $e) {
            return response(config('responses.as_array.not_found'), 404);
        }
        try {
            $productVariant->update($request->only(['price', 'stock']));
            return response(new ProductVariantResource($productVariant), 200);
        } catch (\Exception $e) {
            return response(config('responses.as_array.error'), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $productVariant = ProductVariant::findOrFail($id);
        } catch (\Exception $e) {
            return response(config('responses.as_array.not_found'), 404);
        }

        $deleteCount = $productVariant->delete();

        return response(["deleted" => $deleteCount], 200);
    }
}
