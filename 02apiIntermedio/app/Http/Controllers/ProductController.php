<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse as ApiR;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $data = Product::query()
                // ->orderby('name','asc')
                ->with(['category', 'brand'])
                ->orderby('id', 'desc')
                ->paginate(10);

            return ApiR::success('Products Retrieved Successfully', 200, $data);

            // 
        } catch (Exception $e) {
            return ApiR::error('Error Retrieving Products', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        try {
            // 
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'required|exists:brands,id',
                'name' => 'required|string|min:3|max:255|unique:products,name',
                'description' => 'required|string|min:3|max:255',
                'price' => 'required|numeric|between:0,999999.99',
                'available_quantity' => 'required|integer',
            ]);

            $product = Product::create($request->all());

            return ApiR::success('Product Created Successfully', 201, $product);

            // 
        } catch (ValidationException $e) {
            return ApiR::error('Error Creating Product', 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $product = Product::with(['category', 'brand'])
            ->findOrFail($id);

            return ApiR::success('Product Retrieved Successfully', 200, $product);


            // 
            // } catch (Throwable $th) {
        } catch (ModelNotFoundException $e) {

            return ApiR::error('Error Retrieving Product', 404);
        } catch (Exception $e) {

            return ApiR::error('Error Updating Product', 422);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {

            $product = Product::findOrFail($id);

            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'required|exists:brands,id',
                'name' => [
                    'required',
                    'string',
                    'min:3',
                    'max:255',
                    Rule::unique('products')->ignore($product),
                ],
                'description' => 'required|string|min:3|max:255',
                'price' => 'required|numeric|between:0,999999.99',
                'available_quantity' => 'required|integer',
            ]);

            $product->update($request->all());

            return ApiR::success('Product Updated Successfully', 200, $product);
        } catch (ModelNotFoundException $e) {

            return ApiR::error('Error Updating Product', 422);
        } catch (Exception $e) {

            return ApiR::error('Error Updating Product', 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            $product->delete();

            return ApiR::success('Product Deleted Successfully', 200, $product);


            // 
            // } catch (Throwable $th) {
        } catch (ModelNotFoundException $e) {

            return ApiR::error('Error Deleting Product', 404);
        } catch (Exception $e) {

            return ApiR::error('Error Deleting Product', 422);
        }
    }
}
