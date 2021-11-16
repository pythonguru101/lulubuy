<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ProductController extends BaseController
{
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $products = Product::latest()->paginate(25);
            return $this->handleResponse(ProductResource::collection($products), "Product list");
        } catch (Exception $exception) {
            Log::error("Error on listing products.", ["error" => $exception->getMessage()]);
            return $this->handleError("Error on listing products", [$exception->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductRequest $request
     * @return JsonResponse
     */
    public function store(ProductRequest $request): JsonResponse
    {
        try {
            $product = Product::create($request->safe()->all());
            return $this->handleResponse(new ProductResource($product), "New product was created", 201);
        } catch (Exception $exception) {
            Log::error("Error on creating product.", ["error" => $exception->getMessage(), "data" => $request->all()]);
            return $this->handleError("Error on creating product", [$exception->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $key
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $product = Product::find($id);
            if (is_null($product)) {
                return $this->handleError("Product was not found", []);
            }
            return $this->handleResponse(new ProductResource($product), "Product details");
        } catch (Exception $exception) {
            Log::error("Error on detail product.", ["error" => $exception->getMessage(), "id" => $id]);
            return $this->handleError("Error on detail product", [$exception->getMessage()]);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param ProductRequest $request
     * @param $key
     * @return JsonResponse
     */
    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        try {
            $product->update($request->safe()->all());
            return $this->handleResponse(new ProductResource($product), "Product updated successfully.");
        } catch (Exception $exception) {
            Log::error("Error on detail product.", ["error" => $exception->getMessage(), "product" => $product, 'request' => $request->all()]);
            return $this->handleError("Error on updating product", [$exception->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        try {
            $product->delete();
            return $this->handleResponse(new ProductResource($product), "Product was deleted.");
        } catch (Exception $exception) {
            Log::error("Error on deleting product.", ["error" => $exception->getMessage(), "product" => $product]);
            return $this->handleError("Error on deleting product", [$exception->getMessage()]);
        }
    }


    /**
     * Search for a product
     */
    public function search($title): JsonResponse
    {
        try {
            $products = Product::where('product_name', 'like', '%' . $title . '%')->get();
            if (is_null($products)) {
                return $this->handleError("Product was not found", []);
            }
            return $this->handleResponse(ProductResource::collection($products), "Search product result.");
        } catch (Exception $exception) {
            Log::error("Error on deleting product.", ["error" => $exception->getMessage(), "search" => $title]);
            return $this->handleError("Error on deleting product", [$exception->getMessage()]);
        }
    }
}
