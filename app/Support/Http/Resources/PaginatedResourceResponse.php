<?php

namespace App\Support\Http\Resources;

use Illuminate\Http\Resources\Json\PaginatedResourceResponse as Response;

class PaginatedResourceResponse extends Response
{
    /**
     * Add the pagination information to the response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function paginationInformation($request)
    {
        $paginated = $this->resource->resource->toArray();

        return [
            'current_page' => $paginated['current_page'] ?? 1,
            'per_page' => $paginated['per_page'] ?? 15,
            'total' => $paginated['total'] ?? 0,
        ];
    }
}
