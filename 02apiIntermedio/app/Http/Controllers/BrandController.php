<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse as ApiR;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $data = Brand::query()
                // ->orderby('name','asc')
                ->orderby('id', 'desc')
                ->get();

            return ApiR::success('Brands Retrieved Successfully', 200, $data);

            // 
        } catch (Exception $e) {
            return ApiR::error(['Error Retrieving Brands: ' . $e->getMessage()], 500);
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
                'name' =>           'required|string|min:3|max:100|unique:brands,name',
                'description' =>    'required|string|min:3|max:255'
            ]);

            $brand = Brand::create($request->all());

            return ApiR::success('Brand Created Successfully', 201, $brand);

            // 
        } catch (ValidationException $e) {
            // return ApiR::error(['Error Retrieving Brand: ' . $e->getMessage()], 500);
            return ApiR::error(['Error Creating Brand'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $brand = Brand::findOrFail($id);

            return ApiR::success('Brand Retrieved Successfully', 200, $brand);


            // 
            // } catch (Throwable $th) {
        } catch (ModelNotFoundException $e) {
            // return ApiR::error(['Error Retrieving Brand: ' . $e->getMessage()], 500);
            return ApiR::error(['Error Retrieving Brand'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // 
            $brand = Brand::findOrFail($id);

            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'min:3',
                    'max:100',
                    Rule::unique('Brands')->ignore($brand),
                ],
                'description' =>    [
                    'required',
                    'string',
                    'min:3',
                    'max:255',
                ],
            ]);

            $brand->update($request->all());

            return ApiR::success('Brand Updated Successfully', 201, $brand);

            // 
        } catch (Exception $e) {
            // return ApiR::error(['Error Retrieving Brand: ' . $e->getMessage()], 500);
            return ApiR::error(['Error Updating Brand'], 500);
        } catch (ModelNotFoundException $e) {
            // return ApiR::error(['Error Retrieving Brand: ' . $e->getMessage()], 500);
            return ApiR::error(['Error Updating Brand'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // 
            $brand = Brand::findOrFail($id);

            $brand->delete();

            return ApiR::success('Brand Deleted Successfully', 201, $brand);

            // 
        } catch (Exception $e) {
            return ApiR::error(['Error Deleting Brand'], 404);
        } catch (ModelNotFoundException $e) {
            return ApiR::error(['Error Deleting Brand'], 404);
        }
    }

    public function brandsWithProducts($id)
    {
        try {
            // 
            $brand = Brand::with('products')
                ->findOrFail($id);

            return ApiR::success('Category With Products Retrieved Successfully', 201, $brand);

            // 
        } catch (Exception $e) {
            return ApiR::error('Error Retrieving Category With Products', 404);
        } catch (ModelNotFoundException $e) {
            return ApiR::error('Error Retrieving Category With Products ', 404);
        }
    }


}
