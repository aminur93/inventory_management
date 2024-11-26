<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Helper\GlobalResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Http\Services\Admin\ProductService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $pagination = $request->get('pagination', "true");

        if ($pagination === "true") {

            $countries = $this->productService->index($request);

            return GlobalResponse::success($countries, "All products fetch successful with pagination", \Illuminate\Http\Response::HTTP_OK);

        }

        if ($request->get('pagination') === "false")
        {
            $countries = $this->productService->getAllProducts();

            return GlobalResponse::success($countries, "All products fetch successful", \Illuminate\Http\Response::HTTP_OK);
        }
    }

    public function store(ProductRequest $request)
    {
        try{

            $product = $this->productService->store($request);

            return GlobalResponse::success($product, "Store successful", Response::HTTP_CREATED);

        }catch(ValidationException $exception){

            return GlobalResponse::error($exception->errors(), $exception->getMessage(), $exception->status);

        }catch(Exception $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try{

            $product = $this->productService->show($id);

            return GlobalResponse::success($product, "Fetch by id successful", Response::HTTP_OK);

        }catch(ModelNotFoundException $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_NOT_FOUND);

        }catch (Exception $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(ProductRequest $request, $id)
    {
        try{

            $product = $this->productService->update($request, $id);

            return GlobalResponse::success($product, "Update successful", Response::HTTP_OK);

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

            $this->productService->destroy($id);

            return GlobalResponse::success("", "Delete successful", Response::HTTP_OK);

        }catch(ModelNotFoundException $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_NOT_FOUND);

        }catch (Exception $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}