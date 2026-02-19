<?php

namespace App\Http\Resources\Properties;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="PropertyPhotoResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="file_name", type="string", example="http://example.com/storage/photos/photo.jpg"),
 *     @OA\Property(property="thumbnail", type="string", example="http://example.com/storage/photos/thumb_photo.jpg"),
 *     @OA\Property(property="order", type="integer", example=1)
 * )
 */
class PropertyPhotoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'file_name' => $this->getUrl(),
            'thumbnail' => $this->getUrl('thumbnail'),
            'order' => $this->order_column
        ];
    }
}
