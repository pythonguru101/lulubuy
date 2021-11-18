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
    /**
     * @OA\Get (
     *     path="/api/products/",
     *     operationId="ProductsList",
     *     tags={"Products"},
     *     summary="Products list",
     *     description="Getting list of the products",
     *     security={{"Bearer": {}}},
     *     @OA\RequestBody(
     *          @OA\JsonContent()
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Products List",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
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
     * @OA\Post  (
     *     path="/api/products/",
     *     operationId="ProductCreate",
     *     tags={"Product create"},
     *     summary="Product create",
     *     description="Create new product",
     *     security={{"Bearer": {}}},
     *     @OA\RequestBody(
     *          @OA\JsonContent(),
     *          @OA\Schema(
     *               type="object",
     *               required={"product_name","price", "stock", "barcode", "sku", "ean13", "asin", "isbn"},
     *               @OA\Property(property="product_name", type="text"),
     *               @OA\Property(property="price", type="decimal"),
     *               @OA\Property(property="stock", type="integer"),
     *               @OA\Property(property="barcode", type="text"),
     *               @OA\Property(property="sku", type="text"),
     *               @OA\Property(property="ean13", type="text"),
     *               @OA\Property(property="asin", type="text"),
     *               @OA\Property(property="isbn", type="text"),
     *            )
     *      ),
     *     @OA\Response(
     *          response=201,
     *          description="Product created",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
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
     * @OA\Get (
     *     path="/api/products/{id}",
     *     operationId="ProductDetails",
     *     tags={"Product Details"},
     *     summary="Product Details",
     *     description="Get product details by id",
     *     security={{"Bearer": {}}},
     *     @OA\Property(property="id", type="integer"),
     *     @OA\Response(
     *          response=200,
     *          description="Product details",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
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
     * @OA\Put (
     *     path="/api/products/{id}",
     *     operationId="ProductUpdate",
     *     tags={"Product Update"},
     *     summary="Product Update",
     *     description="Update existing product",
     *     security={{"Bearer": {}}},
     *     @OA\RequestBody(
     *          @OA\JsonContent(),
     *          @OA\Schema(
     *               type="object",
     *               required={"product_name","price", "stock", "barcode", "sku", "ean13", "asin", "isbn"},
     *               @OA\Property(property="product_name", type="text"),
     *               @OA\Property(property="price", type="decimal"),
     *               @OA\Property(property="stock", type="integer"),
     *               @OA\Property(property="barcode", type="text"),
     *               @OA\Property(property="sku", type="text"),
     *               @OA\Property(property="ean13", type="text"),
     *               @OA\Property(property="asin", type="text"),
     *               @OA\Property(property="isbn", type="text"),
     *            )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Product Updated",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
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
     * @OA\Delete (
     *     path="/api/products/{id}",
     *     operationId="ProductDelete",
     *     tags={"Product Delete"},
     *     summary="Product Delete",
     *     description="Delete product by id",
     *     security={{"Bearer": {}}},
     *     @OA\Property(property="id", type="integer"),
     *     @OA\Response(
     *          response=200,
     *          description="Product Deleted",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
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
     * @OA\Get (
     *     path="/api/products/search/{title}",
     *     operationId="Productsearch",
     *     tags={"Product Search"},
     *     summary="Product search",
     *     description="Search for a product by title",
     *     security={{"Bearer": {}}},
     *     @OA\Property(property="title", type="text"),
     *     @OA\Response(
     *          response=200,
     *          description="Product Result",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
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
