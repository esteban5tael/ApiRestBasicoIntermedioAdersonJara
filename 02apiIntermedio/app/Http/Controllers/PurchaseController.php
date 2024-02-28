<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse as ApiR;
use App\Models\Product;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $data = Purchase::query()
                // ->orderby('name','asc')
                ->with(['products'])
                ->orderby('id', 'desc')
                ->paginate(10);

            return ApiR::success('Purchases Retrieved Successfully', 200, $data);

            // 
        } catch (Exception $e) {
            return ApiR::error('Error Retrieving Purchases', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // 
            $products = $request->input('products');

            // validar productos
            if (empty($products)) {
                return ApiR::error('Products Were Not Provided', 400);
            }

            //validar lista de productos

            $validator = Validator::make(
                $request->all(),
                [
                    'products' => 'required|array',
                    'products.*.product_id' => 'required|integer|exists:products,id',
                    'products.*.quantity' => 'required|integer|min:1',
                ]
            );

            if ($validator->fails()) {
                return ApiR::error('Invalid Data in the Product List', 400, $validator->errors());
            }

            // validar productos duplicados

            $productsIds = array_column($products, 'product_id');

            if (count($productsIds) !== count(array_unique($productsIds))) {
                return ApiR::error('Duplicate Products in the List', 400);
            }

            $purchaseTotal = 0;
            $subtotal = 0;
            $purchaseItems = [];

            //iteracion de productos para calcular el total

            foreach ($products as $product) {

                $productB = Product::find($product['product_id']);

                if (!$productB) {
                    return ApiR::error('Product Not Found', 404);
                }


                // validar la cantidad disponible

                if ($productB->available_quantity < $product['quantity']) {
                    return ApiR::error('Product Available Quantity Not Enough', 404);

                    /* return response()->json([
                        'productB->available_quantity' => $productB->available_quantity,
                        'product[quantity]' => $product['quantity']
                    ]); */
                }

                //actualizacion cantidad disponible de cada producto

                $productB->available_quantity -= $product['quantity'];
                $productB->save();

                // calculo de los importes
                $subtotal = $productB->price * $product['quantity'];

                $purchaseTotal += $subtotal;

                // items de la compra
                $purchaseItems[] = [
                    'product_id' => $productB->id,
                    'price' => $productB->price,
                    'quantity' => $product['quantity'],
                    'subtotal' => $subtotal,
                ];
            }

            // registro tabla compras
            $purchase = Purchase::create([
                'subtotal' => $subtotal,
                'total' => $purchaseTotal,
            ]);

            // asociar productos a la compra con sus cantidades y subtotales

            $purchase->products()->attach($purchaseItems);

            return ApiR::success('Purchase Registered Successfully', 201, $purchase);


            // 
        } catch (QueryException $e) {
            return ApiR::error('Error Creating Purchase (QueryException): ' . $e->getMessage(), 500);
        } catch (ValidationException $e) {
            return ApiR::error('Error Creating Purchase (ValidationException):' . $e->getMessage(), 500);
        } catch (Exception $e) {
            return ApiR::error('Error Creating Purchase (Exception): ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {

            $data = Purchase::
                // ->orderby('name','asc')
                with(['products'])
                ->findOrFail($id);

            return ApiR::success('Purchase Retrieved Successfully', 200, $data);

            // 
        } catch (Exception $e) {
            return ApiR::error('Error Retrieving Purchase', 500);
        } catch (ModelNotFoundException $e) {

            return ApiR::error('Error Retrieving Purchase', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        //
    }
}
