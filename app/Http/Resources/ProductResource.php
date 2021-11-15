<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'slug' => $this->slug,
            'barcode' => $this->barcode,
            'sku' => $this->sku,
            'ean13' => $this->ean13,
            'asin' => $this->asin,
            'isbn' => $this->isbn,
            'price' => $this->price,
            'stock' => $this->stock,
            'created_at' => Carbon::parse($this->created_at),
            'user' => new UserResource($this->user)
        ];
    }

}
