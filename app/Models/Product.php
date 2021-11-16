<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "product_name", "firebase_key", "barcode", "sku", "ean13", "asin", "isbn", "price", "stock", "user_id",
    ];


    /**
     * Users relation
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Populate user_id on creating new product
     */
    public function save($options = [])
    {
        $this->user_id = auth()->id();
        $this->slug = Str::slug($this->product_name);
        parent::save($options);
    }


}


