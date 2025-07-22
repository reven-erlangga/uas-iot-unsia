<?php

namespace App\Http\Resources\Telemetry;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Transform the paginated data to camelCase while preserving structure
        $data = $this->resource->toArray();
        
        // Transform the items using ItemResource
        $data['data'] = ItemResource::collection($this->resource->items())->toArray($request);
        
        // Transform pagination meta keys to camelCase
        $data['currentPage'] = $data['current_page'];
        $data['lastPage'] = $data['last_page'];
        $data['perPage'] = $data['per_page'];
        $data['firstPageUrl'] = $data['first_page_url'];
        $data['lastPageUrl'] = $data['last_page_url'];
        $data['nextPageUrl'] = $data['next_page_url'];
        $data['prevPageUrl'] = $data['prev_page_url'];
        
        // Remove snake_case keys
        unset(
            $data['current_page'],
            $data['last_page'], 
            $data['per_page'],
            $data['first_page_url'],
            $data['last_page_url'],
            $data['next_page_url'],
            $data['prev_page_url']
        );
        
        return $data;
    }
}
