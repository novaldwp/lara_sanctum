<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\v1\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;

class ProductController extends BaseController
{
    public function index()
    {
        $product = Product::all();

        if($product->count() == 0) {
            return $this->sendResponse(ProductResource::collection($product), 'No Product Available');
        }

        return $this->sendResponse(ProductResource::collection($product), 'Product Retrieved Successfully');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'  => 'required',
            'detail' => 'required'
        ]);

        $product = Product::create($request->all());

        return $this->sendResponse(new ProductResource($product), 'Product Created Successfully');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        if(!$product)
        {
            return $this->sendError('Product cannot be found');
        }

        return $this->sendResponse(new ProductResource($product), 'Product Retrieved Successfully');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if(!$product)
        {
            return $this->sendError('Product with this id cannot be found');
        }

        $product->update($request->all());

        if($product)
        {
            return response()->json([
                'status' => 1,
                'message' => 'Product updated successfully'
            ], 200);
        }

        return response()->json([
            'status' => 0,
            'message' => 'Product cannot be updated'
        ], 500);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if(!$product)
        {
            return $this->sendError('Product with this id cannot be found');
        }

        $product->delete();

        return response()->json([
            'status' => 1,
            'message' => 'Product deleted successfully'
        ]);
    }
}
