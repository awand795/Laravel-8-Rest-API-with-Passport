<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Products;
use Validator;
use App\Http\Resources\Products as ProductResource;

class ProductController extends BaseController{
    public function index(){
        $products = Products::all();

        return $this->sendResponse(ProductResource::collection($products),'Product Retrieve Successfully!');
    }

    public function store(Request $request){
        $input = $request->all();

        $validator = Validator::make($input,[
            'name'=>'required',
            'detail'=>'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.',$validator->errors());
        }

        $product = Products::create($input);

        return $this->sendResponse(new ProductResource($product),'Product created Successfully!');
    }

    public function show($id){
        $product = Products::find($id);

        if(is_null($product)){
            return $this->sendError('Product not found');
        }

        return $this->sendResponse(new ProductResource($product),'Product Retrieved Succesffully');
    }

    public function update(Request $request,Products $product){
        $input = $request->all();

        $validator = Validator::make($input,[
            'name'=>'required',
            'detail'=>'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.',$validator->errors());
        }

        $product->name = $input['name'];
        $product->detail = $input['detail'];
        $product->save();
    }

    public function destroy(Products $product){
        $product->delete();
        return $this->sendResponse([],'Product deleted successfully.');
    }
}