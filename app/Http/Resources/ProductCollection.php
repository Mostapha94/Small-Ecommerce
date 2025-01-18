<?php


namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'category' => $product->category ? $product->category->name : null,
                ];
            }),
            'links' => [
                'next' => $this->nextCursor() ? $this->nextCursor()->encode() : null,
                'prev' => $this->previousCursor() ? $this->previousCursor()->encode() : null,
            ],
        ];
    }
}
