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
     * create new document on firebase
     * @param $data
     * @param string $document
     * @return string|null
     */
    public static function create($data, string $document = 'product'): ?string
    {
        try {
            $firebaseProduct = self::getDb()
                ->getReference(self::getFirebaseUrl($document))
                ->push(
                    $data
                );
            return $firebaseProduct->getKey();
        } catch (Exception $exception) {
            Log::error("Couldn't create the document into Firebase.", ['error' => $exception->getMessage(), 'document' => $document, 'data' => $data]);
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
     * Update document to firebase
     * @param $data
     * @param string $document
     * @return bool
     */
    public static function update($data, string $document = 'product'): bool
    {
        try {
            $key = $data['firebase_key'];
            unset($data['firebase_key']);
            self::getDb()
                ->getReference(self::getFirebaseUrl($document, $key))
                ->update($data);
            return true;
        } catch (Exception $exception) {
            Log::error("Couldn't update the document on Firebase.", ['error' => $exception->getMessage(), 'document' => $document, 'data' => $data]);
            return false;
        }
    }

    /**
     * Delete a document from firebase
     * @param $key
     * @param string $document
     * @return bool
     */
    public static function delete($key, string $document = 'product'): bool
    {
        try {
            self::getDb()
                ->getReference(self::getFirebaseUrl($document, $key))
                ->remove();
            return true;
        } catch (Exception $exception) {
            Log::error("Couldn't delete the document from Firebase", ['error' => $exception->getMessage(), 'document' => $document, 'key' => $key]);
            return false;

        }
    }


}
