<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Reviews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewsController extends Controller
{
    public function showAllReviews()
    {
        $reviews = Reviews::all();

        return response()->json([
            'status' => true,
            'message' => 'Got all reviews',
            'data' => $reviews
        ]);
    }

    public function getReviewByproductCategory($category)
    {
        $category = str_replace('-', ' ', $category);

        $reviews = Products::rightJoin('reviews', 'reviews.id_producto', '=', 'products.id')
            ->select('products.id', 'products.categoria', 'products.nombre', 'reviews.comments', 'reviews.stars')
            ->where('products.categoria', $category)->get();


        if ($reviews->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No reviews under this category',
                'data' => 'No data'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Reviews by category: ' . $category,
            'data' => $reviews
        ], 200);


    }

    public function getReviewByProductName($name)
    {
        $name = str_replace('-', ' ', $name);

        $reviews = Products::rightJoin('reviews', 'reviews.id_producto', '=', 'products.id')
            ->select('products.id', 'products.categoria', 'products.nombre', 'reviews.comments', 'reviews.stars')
            ->where('products.nombre', $name)->get();


        if ($reviews->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No reviews under this name',
                'data' => 'No data'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Reviews by name: ' . $name,
            'data' => $reviews
        ], 200);
    }

    public function createReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_producto' => 'required|numeric',
            'comments' => 'required|string',
            'stars' => 'required|numeric|min:0|max:5'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 409);
        }


        $id_producto = $request->input('id_producto');
        $producto = Products::find($id_producto);

        if (!$producto) {
            return response()->json([
                'status' => false,
                'message' => 'No product with id ' . $id_producto . ' was found',
                'data' => 'No data'
            ], 404);
        }

        $review = Reviews::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'New review has been created',
            'data' => $review
        ]);

    }

}
