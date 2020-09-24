<?php

namespace App\Support\Domains\Search\Contracts;

/**
 * Interface SearchableContract
 *
 * @method  mixed  search($value = null, bool $advanced = false, callable $callback = null)
 */
interface SearchableContract
{
    /**
     * Apply the search to a query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @param  null                                                                      $value
     * @param  bool                                                                      $advanced
     * @param  callable|null                                                             $callback
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function applySearch(
        $query,
        $value = null,
        bool $advanced = false,
        callable $callback = null
    );

    /**
     * Get the searchable columns.
     *
     * @return array
     */
    public function searchable(): array;
}
