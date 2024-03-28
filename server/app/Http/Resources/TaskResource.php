<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Crypt;

class TaskResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $collection = $this->collection->transform(function ($item) {
            return [
                "id"         => Crypt::encryptString($item->id),
                "title"      => $item->title,
                "filename"   => $item->filename,
                "status"     => $item->status,
                "created_at" => Carbon::parse($item->created_at)->format("d-m-Y"),
            ];
        });

        return [
            "data" => $collection
        ];
    }
}
