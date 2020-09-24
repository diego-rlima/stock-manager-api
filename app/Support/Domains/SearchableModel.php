<?php

namespace App\Support\Domains;

use Illuminate\Support\Arr;
use App\Support\Domains\Search\Traits\Searchable;
use App\Support\Domains\Search\Contracts\SearchableContract;

abstract class SearchableModel extends Model implements SearchableContract
{
    use Searchable;

    /**
     * Get the searchable columns.
     *
     * @return array
     */
    public function searchable(): array
    {
        return Arr::wrap($this->searchable ?? []);
    }
}
