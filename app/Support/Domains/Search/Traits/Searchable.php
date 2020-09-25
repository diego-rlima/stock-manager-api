<?php

namespace App\Support\Domains\Search\Traits;

use App\Support\Domains\Search\Contracts\SearchContract;

/**
 * Trait Searchable
 *
 * @method  array  searchable()
 * @method  mixed  search($value = null, bool $advanced = false, callable $callback = null)
 */
trait Searchable
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
    ) {
        $search = resolve(SearchContract::class);

        $search->getSimple()->setTerm($value);
        $search->getSimple()->setColumns($this->searchable());

        $search->useAdvanced($advanced);

        if (method_exists($this, 'searchMutators')) {
            $this->searchMutators($search);
        }

        if (method_exists($this, 'searchAdvancedSettings')) {
            $this->searchAdvancedSettings($search->getAdvanced());
        }

        if (is_callable($callback)) {
            $callback($query, $search, $value);
        }

        return $search->query($query);
    }

    /**
     * Scope a query to apply the search.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @param  null                                                                      $value
     * @param  bool                                                                      $advanced
     * @param  callable|null                                                             $callback
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function scopeSearch(
        $query,
        $value = null,
        bool $advanced = false,
        callable $callback = null
    ) {
        return $this->applySearch($query, $value, $advanced, $callback);
    }
}
