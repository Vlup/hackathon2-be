<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return $this->map(function ($event) {
            return [
                'id' => $event->id,
                'name' => $event->name,
                'location' => $event->location,
                'image' => $event->image,
                'description' => $event->description,
                'price_after_discount' => "Rp. " . number_format(max($event->price - $event->discount, 0)),
                'price' => "Rp. " . number_format($event->price),
                'is_sold' => !($event->stock > 0),
                'stock' => $event->stock,
                'time' => $event->time->setTimezone('Asia/Jakarta')->format('l, d F Y H:i')
            ]; 
        });
    }
}
