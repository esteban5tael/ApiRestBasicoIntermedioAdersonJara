<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse as ApiR;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $data = Category::query()
                // ->orderby('name','asc')
                ->orderby('id', 'desc')
                ->get();

            return ApiR::success('Categories Retrieved Successfully', 200, $data);

            // 
        } catch (Exception $e) {
            return ApiR::error(['Error Retrieving Categories: ' . $e->getMessage()], 500);
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
                'name' =>           'required|string|min:3|max:100|unique:categories,name',
                'description' =>    'required|string|min:3|max:255'
            ]);

            $category = Category::create($request->all());

            return ApiR::success('Category Created Successfully', 201, $category);

            // 
        } catch (ValidationException $e) {
            // return ApiR::error(['Error Retrieving Category: ' . $e->getMessage()], 500);
            return ApiR::error('Error Creating Category', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);

            return ApiR::success('Category Retrieved Successfully', 200, $category);


            // 
            // } catch (Throwable $th) {
        } catch (ModelNotFoundException $e) {
            // return ApiR::error(['Error Retrieving Category: ' . $e->getMessage()], 500);
            return ApiR::error(['Error Retrieving Category'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // 
            $category = Category::findOrFail($id);

            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'min:3',
                    'max:100',
                    Rule::unique('categories')->ignore($category),
                ],
                'description' =>    [
                    'required',
                    'string',
                    'min:3',
                    'max:255',
                ],
            ]);

            $category->update($request->all());

            return ApiR::success('Category Updated Successfully', 201, $category);

            // 
        } catch (Exception $e) {
            // return ApiR::error(['Error Retrieving Category: ' . $e->getMessage()], 500);
            return ApiR::error(['Error Updating Category'], 500);
        } catch (ModelNotFoundException $e) {
            // return ApiR::error(['Error Retrieving Category: ' . $e->getMessage()], 500);
            return ApiR::error(['Error Updating Category'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // 
            $category = Category::findOrFail($id);

            $category->delete();

            return ApiR::success('Category Deleted Successfully', 201, $category);

            // 
        } catch (Exception $e) {
            return ApiR::error(['Error Deleting Category'], 404);
        } catch (ModelNotFoundException $e) {
            return ApiR::error(['Error Deleting Category'], 404);
        }
    }


    public function categoryWithProducts($id)
    {
        try {
            // 
            $category = Category::with('products')
                ->findOrFail($id);

            return ApiR::success('Category With Products Retrieved Successfully', 201, $category);

            // 
        } catch (Exception $e) {
            return ApiR::error('Error Retrieving Category With Products', 404);
        } catch (ModelNotFoundException $e) {
            return ApiR::error('Error Retrieving Category With Products ', 404);
        }
    }
}
