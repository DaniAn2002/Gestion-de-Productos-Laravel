<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    public function getAllProductsEnabled()
    {

        $products = Products::where('disabled', false)->get();

        return response()->json([
            'status' => true,
            'message' => 'Got alll enabled products',
            'data' => $products
        ], 200);

    }

    public function getAllProducts()
    {
        $products = Products::all();

        return response()->json([
            'status' => true,
            'message' => 'Got all products',
            'data' => $products
        ], 200);
    }

    public function getSingleProduct($id)
    {

        $products = Products::find($id);

        if (!$products) {
            return response()->json([
                'status' => false,
                'message' => 'Product was not found',
                'data' => 'No data'
            ], 404);
        }

        if ($products->disabled) {
            return response()->json([
                'status' => false,
                'message' => 'Product has been eliminated',
                'data' => 'No data'
            ], 400);
        }

        return response()->json([
            'status' => true,
            'message' => 'Product with id ' . $id . ' was found',
            'data' => $products
        ]);

    }

    public function addProducts(Request $request)
    {
        $repeated_product = Products::where('nombre', $request->nombre)->first();

        if ($repeated_product) {
            return response()->json([
                'status' => false,
                'message' => 'Product already exists on database',
                'data' => 'No data'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'categoria' => 'required|string',
            'nombre' => 'required|string',
            'marca' => 'required|string',
            'precio' => 'required|string',
            'stock' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 409);
        }

        $products = Products::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Product has been added to the database',
            'data' => $products
        ], 200);

    }

    public function updateProducts(Request $request, $id)
    {
        $product = Products::find($id);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product with id ' . $id . ' does not exist',
                'data' => 'No data'
            ], 404);
        }

        $product->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Product with id ' . $id . ' has been updated',
            'data' => $product
        ], 200);
    }

    public function disableProduct($id)
    {

        $product = Products::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product with id ' . $id . ' does not exist',
                'data' => 'No data'
            ], 404);
        }

        if ($product->disabled) {
            return response()->json([
                'status' => false,
                'message' => 'Product with id ' . $id . ' has been already disabled',
                'data' => 'No data'
            ], 409);
        }

        $product->update(['disabled' => true]);
        return response()->json([
            'status' => true,
            'message' => 'Product with id ' . $id . ' has been disabled',
            'data' => $product
        ], 200);

    }

    public function enableProduct($id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product with id ' . $id . ' does not exist',
                'data' => 'No data'
            ], 404);
        }

        if (!($product->disabled)) {
            return response()->json([
                'status' => false,
                'message' => 'Product with id ' . $id . ' is already enabled',
                'data' => $product
            ], 409);
        }

        $product->update(['disabled' => false]);
        return response()->json([
            'status' => true,
            'message' => 'Product with id ' . $id . ' has been enabled',
            'data' => $product
        ], 200);
    }

}
