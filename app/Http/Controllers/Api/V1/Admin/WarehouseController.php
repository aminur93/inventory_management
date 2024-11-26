<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Helper\GlobalResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WarehouseRequest;
use App\Http\Services\Admin\WareHouseService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class WarehouseController extends Controller
{
    private $wareHouseService;

    public function __construct(WareHouseService $wareHouseService)
    {
        $this->wareHouseService = $wareHouseService;
    }

    public function index(Request $request)
    {
        $pagination = $request->get('pagination', "true");

        if ($pagination === "true") {

            $countries = $this->wareHouseService->index($request);

            return GlobalResponse::success($countries, "All WareHouse fetch successful with pagination", \Illuminate\Http\Response::HTTP_OK);

        }

        if ($request->get('pagination') === "false")
        {
            $countries = $this->wareHouseService->getAllWareHouse();

            return GlobalResponse::success($countries, "All WareHouse fetch successful", \Illuminate\Http\Response::HTTP_OK);
        }
    }

    public function store(WarehouseRequest $request)
    {
        try{

            $warehouse = $this->wareHouseService->store($request);

            return GlobalResponse::success($warehouse, "Store successful", Response::HTTP_CREATED);

        }catch(ValidationException $exception){

            return GlobalResponse::error($exception->errors(), $exception->getMessage(), $exception->status);

        }catch(Exception $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try{

            $warehouse = $this->wareHouseService->show($id);

            return GlobalResponse::success($warehouse, "Fetch by id successful", Response::HTTP_OK);

        }catch(ModelNotFoundException $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_NOT_FOUND);

        }catch (Exception $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(WarehouseRequest $request, $id)
    {
        try{

            $warehouse = $this->wareHouseService->update($request, $id);

            return GlobalResponse::success($warehouse, "Update successful", Response::HTTP_OK);

        }catch(ValidationException $exception){

            return GlobalResponse::error($exception->errors(), $exception->getMessage(), $exception->status);

        }catch(ModelNotFoundException $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_NOT_FOUND);

        }catch (Exception $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try{

            $this->wareHouseService->destroy($id);

            return GlobalResponse::success("", "Delete successful", Response::HTTP_OK);

        }catch(ModelNotFoundException $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_NOT_FOUND);

        }catch (Exception $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}