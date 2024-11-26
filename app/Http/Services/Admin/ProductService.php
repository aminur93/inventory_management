<?php

namespace App\Http\Services\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function index(Request $request)
    {
        $products = Product::with('warehouse');

        if ($request->has('sortBy') && $request->has('sortDesc')) {

            $sortBy = $request->query('sortBy');

            $sortDesc = $request->query('sortDesc') === 'true' ? 'desc' : 'asc';

            $products = $products->orderBy($sortBy, $sortDesc);

        } else {

            $products = $products->orderBy('id', 'desc');
        }

        $searchValue = $request->input('search');
        $itemsPerPage = 10;

        if ($searchValue)
        {
            $products->where(function ($query) use ($searchValue) {
                $query->where('name', 'like', '%' . $searchValue . '%');
            });


            if($request->has('itemsPerPage')) {

                $itemsPerPage = $request->get('itemsPerPage');

                return $products->paginate($itemsPerPage, ['*'], $request->get('page'));
            }
        }

        if ($request->has('itemsPerPage'))
        {
            $itemsPerPage = $request->get('itemsPerPage');
        }


        return $products->paginate($itemsPerPage);
    }

    public function getAllProducts()
    {
        $products = Product::latest()->get();

        return $products;
    }

    public function store(Request $request)
    {
        try {

            $product = new Product();

            $product->warehouse_id = $request->warehouse_id;
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->qty = $request->qty;

            $product->save();

            DB::commit();

            return $product;

        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        return $product;
    }

    public function update(Request $request, $id)
    {
        try {

            $product = Product::findOrFail($id);

            $product->warehouse_id = $request->warehouse_id ?? $product->warehouse_id;
            $product->name = $request->name ?? $product->name;
            $product->description = $request->description ?? $product->description;
            $product->price = $request->price ?? $product->price;
            $product->qty = $request->qty ?? $product->qty;

            $product->save();

            DB::commit();

            return $product;

        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
    }
}