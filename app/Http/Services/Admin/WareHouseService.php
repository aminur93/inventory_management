<?php

namespace App\Http\Services\Admin;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WareHouseService
{
    public function index(Request $request)
    {
        $warehouses = Warehouse::with('products');

        if ($request->has('sortBy') && $request->has('sortDesc')) {

            $sortBy = $request->query('sortBy');

            $sortDesc = $request->query('sortDesc') === 'true' ? 'desc' : 'asc';

            $warehouses = $warehouses->orderBy($sortBy, $sortDesc);

        } else {

            $warehouses = $warehouses->orderBy('id', 'desc');
        }

        $searchValue = $request->input('search');
        $itemsPerPage = 10;

        if ($searchValue)
        {
            $warehouses->where(function ($query) use ($searchValue) {
                $query->where('name', 'like', '%' . $searchValue . '%');
            });


            if($request->has('itemsPerPage')) {

                $itemsPerPage = $request->get('itemsPerPage');

                return $warehouses->paginate($itemsPerPage, ['*'], $request->get('page'));
            }
        }

        if ($request->has('itemsPerPage'))
        {
            $itemsPerPage = $request->get('itemsPerPage');
        }


        return $warehouses->paginate($itemsPerPage);
    }

    public function getAllWareHouse()
    {
        $warehouses = Warehouse::with('products')->latest()->get();

        return $warehouses;
    }

    public function store(Request $request)
    {
        try {

            $warehouse = new Warehouse();

            $warehouse->name = $request->name;
            $warehouse->location = $request->location;

            $warehouse->save();

            DB::commit();

            return $warehouse;

        } catch (\Throwable $th) {

            DB::rollBack();

            throw $th;
        }
    }

    public function show($id)
    {
        $warehouse = Warehouse::findOrFail($id);

        return $warehouse;
    }

    public function update(Request $request, $id)
    {
        try {

            $warehouse = Warehouse::findOrFail($id);

            $warehouse->name = $request->name ?? $warehouse->name;
            $warehouse->location = $request->location ?? $warehouse->location;

            $warehouse->save();

            DB::commit();

            return $warehouse;

        } catch (\Throwable $th) {

            DB::rollBack();

            throw $th;
        }
    }

    public function destroy($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->delete();
    }
}