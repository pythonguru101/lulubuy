<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

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
        $products = $this->getDb()->getReference()->orderByKey()->getSnapshot();
        return $this->handleResponse([$products->getValue()], "Product list.");

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductRequest $request
     * @return JsonResponse
     */
    public function store(ProductRequest $request): JsonResponse
    {
        $newProduct = $this->getDb()
            ->getReference('products')
            ->push(
                $request->safe()->all()
            );
        $result = $newProduct->getValue();
        $result['key'] = $newProduct->getKey();
        return $this->handleResponse($result, "New product was created", 201);
    }

    /**
     * Display the specified resource.
     *
     * @param $key
     * @return JsonResponse
     */
    public function show($key): JsonResponse
    {
        $url = env('FIREBASE_DATABASE_NAME').'/' . $key;
        $product = $this->getDb()->getReference($url)->getValue();
        $product['key'] = $key;
        if (is_null($product)) {
            return $this->handleError("Product was not found", []);
        }
        return $this->handleResponse($product, "Product details");
    }


    /**
     * Update the specified resource in storage.
     *
     * @param ProductRequest $request
     * @param $key
     * @return JsonResponse
     */
    public function update(ProductRequest $request, $key): JsonResponse
    {
        $url = env('FIREBASE_DATABASE_NAME').'/' . $key;
        $product = $this->getDb()->getReference($url)->update($request->safe()->all());
        return $this->handleResponse($product, "Product updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($key): JsonResponse
    {
        $url = env('FIREBASE_DATABASE_NAME').'/' . $key;
        $product = $this->getDb()->getReference($url)->remove();
        return $this->handleResponse($product, "Product was deleted.");
    }

}
