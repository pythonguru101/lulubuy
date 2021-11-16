<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\Firebase;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     *
     * @param Product $product
     * @return void
     */
    public function created(Product $product)
    {
        $firebaseKey = Firebase::create($product->toArray());
        if (!empty($firebaseKey)) {
            $product->firebase_key = $firebaseKey;
            $product->update(['firebase_key' => $firebaseKey]);
        }
    }

    /**
     * Handle the Product "updated" event.
     *
     * @param Product $product
     * @return void
     */
    public function updated(Product $product)
    {
        if (!empty($product->firebase_key)) {
            Firebase::update($product->toArray());
        }
    }

    /**
     * Handle the Product "deleted" event.
     *
     * @param Product $product
     * @return void
     */
    public function deleted(Product $product)
    {
        if (!empty($product->firebase_key)) {
            Firebase::delete($product->firebase_key);
        }
    }

    /**
     * Handle the Product "restored" event.
     *
     * @param Product $product
     * @return void
     */
    public function restored(Product $product)
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     *
     * @param Product $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        if (!empty($product->firebase_key)) {
            Firebase::delete($product->firebase_key);
        }
    }
}
