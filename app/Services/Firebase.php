<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Database;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class Firebase
{

    /**
     * create new product on firebase
     * @param $data
     * @return string|null
     */
    public static function createProduct($data): ?string
    {
        try {
            $firebaseProduct = self::getDb()
                ->getReference(env('FIREBASE_DATABASE_PRODUCTS'))
                ->push(
                    $data
                );
            return $firebaseProduct->getKey();
        } catch (Exception $exception) {
            Log::error("Couldn't create the product into Firebase.", ['error' => $exception->getMessage(), 'data' => $data]);
            return null;
        }
    }

    /**
     * Connect to firebase database
     * @return Database
     */
    public static function getDb(): Database
    {
        $serviceAccount = ServiceAccount::fromJsonFile(storage_path(env('FIREBASE_CREDENTIALS_PATH')));
        $firebase = (new Factory)->withServiceAccount($serviceAccount)
            ->withDatabaseUri(env('FIREBASE_DATABASE_URI'))
            ->create();
        return $firebase->getDatabase();
    }

    /**
     * Update Product to firebase
     * @param $data
     * @return bool
     */
    public static function updateProduct($data): bool
    {
        try {
            $key = $data['firebase_key'];
            unset($data['firebase_key']);
            self::getDb()
                ->getReference(self::getFirebaseUrl('product', $key))
                ->update($data);
            return true;
        } catch (Exception $exception) {
            Log::error("Couldn't update the product on Firebase.", ['error' => $exception->getMessage(), 'data' => $data]);
            return false;
        }
    }

    /**
     * Get firebase database name or path with table name
     * @param string $database
     * @param null $key
     * @return mixed|string
     */
    public static function getFirebaseUrl(string $database = 'product', $key = null)
    {
        $url = '';
        if ($database == 'product') {
            $url = env('FIREBASE_DATABASE_PRODUCTS');
        } elseif ($database == 'user') {
            $url = env('FIREBASE_DATABASE_USERS');
        }

        return (!empty($key)) ? $url . '/' . $key : $url;
    }

    /**
     * Delete a product from firebase
     * @param $key
     * @return bool
     */
    public static function deleteProduct($key): bool
    {
        try {
            self::getDb()
                ->getReference(self::getFirebaseUrl('product', $key))
                ->remove();
            return true;
        } catch (Exception $exception) {
            Log::error("Couldn't delete the product from Firebase", ['error' => $exception->getMessage(), 'key' => $key]);
            return false;

        }
    }


}
