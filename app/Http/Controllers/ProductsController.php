<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Rules\IsUserOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|string',
            'direction' => 'required|string',
        ]);

        $products = Product::limit(10)
            ->orderBy($validated['order'], $validated['direction'])
            ->get();

        return response()->json($products);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', new IsUserOwner()],
            'title' => 'required|string',
            'price' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()],
                422
            );
        }

        Product::create($validator->validated());
        return response()->json();
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:products',
            'user_id' => ['required', new IsUserOwner()],
            'title' => 'required|string',
            'price' => 'required|integer',

        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()],
                403
            );
        }
        $validated = $validator->validated();
        Product::whereId($validated['id'])->update($validated);

        return response()->json();
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:products',
            'user_id' => ['required', new IsUserOwner()],
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()],
                403
            );
        }
        $validated = $validator->validated();
        Product::whereId($validated['id'])->delete();

        return response()->json();
    }
}
