<?php

namespace App\Support\Domains\Search;

use Illuminate\Support\Facades\Request;
use App\Support\Domains\Search\Contracts\AdvancedSearchContract;

class AdvancedSearch implements AdvancedSearchContract
{
    /**
     * The collection of queries.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $queries;

    /**
     * The search fields prefix.
     *
     * @var string
     */
    protected string $prefix = 'sf_';

    /**
     * The query to be applied before the search query.
     *
     * @var callable|null
     */
    protected $beforeQuery;

    /**
     * The query to be applied after the search query.
     *
     * @var callable|null
     */
    protected $afterQuery;

    /**
     * The search builder instance.
     *
     * @var \App\Support\Domains\Search\Contracts\SearchContract
     */
    protected Contracts\SearchContract $builder;

    /**
     * AdvancedSearch constructor.
     *
     * @param  Search  $search
     */
    public function __construct(Search $search)
    {
        $this->builder = $search;
        $this->queries = collect([]);
    }

    /**
     * Apply the search query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function query($query)
    {
        $query->where(function ($query) {
            $this->applyBeforeQuery($query);

            $this->queries->each(function ($field, $name) use ($query) {
                $value = Request::get($name);

                if (isset($field['relationship'])) {
                    $this->applyRelationQuery(
                        $query,
                        $field['relationship'],
                        $field['callback'],
                        $value
                    );

                    return;
                }

                $this->applyNormalQuery(
                    $query,
                    $field['column'],
                    $field['operator'],
                    $value
                );
            });

            $this->applyAfterQuery($query);
        });

        return $query;
    }

    /**
     * Apply the relation query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @param  string                                                                    $relation
     * @param  callable                                                                  $callback
     * @param  mixed                                                                     $value
     */
    protected function applyRelationQuery(
        $query,
        string $relation,
        callable $callback,
        $value = null
    ): void {
        if (empty($value)) {
            return;
        }

        $query->whereHas($relation, function ($query) use (
            $callback,
            $value
        ) {
            call_user_func($callback, $query, $value, $this->builder);
        });
    }

    /**
     * Apply the normal query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @param  string                                                                    $column
     * @param  string                                                                    $operator
     * @param  mixed                                                                     $value
     */
    protected function applyNormalQuery(
        $query,
        string $column,
        string $operator,
        $value = null
    ): void {
        $value = $this->builder->termFormatted($value, $column, ($operator == 'like'));

        if (
            !empty($value)
            || (!is_null($value) && intval($value) === 0 && $value !== '')
        ) {
            if (is_array($value)) {
                $query->whereIn($column, $value);
                return;
            }

            $query->where($column, $operator, $value);
        }
    }

    /**
     * Add a column to be searched.
     *
     * @param  string       $name
     * @param  string|null  $column
     * @param  string       $operator
     * @return self
     */
    public function addColumn(
        string $name,
        string $column = null,
        string $operator = 'LIKE'
    ): AdvancedSearchContract {
        if (!$column) {
            $column = $name;
        }

        $name = $this->prefix . ucfirst($name);

        $this->queries->put($name, compact('column', 'operator'));

        return $this;
    }

    /**
     * Get the columns to search.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return $this->queries->pluck('column')->toArray();
    }

    /**
     * Add a relationship to be searched.
     *
     * @param  string    $name
     * @param  string    $relationship
     * @param  callable  $callback
     * @return self
     */
    public function addRelationship(
        string $name,
        string $relationship,
        callable $callback
    ): AdvancedSearchContract {
        $name = $this->prefix . ucfirst($name);

        $this->queries->put($name, compact('relationship', 'callback'));

        return $this;
    }

    /**
     * Get the relationships.
     *
     * @return array|null
     */
    public function getRelationships(): ?array
    {
        return $this->queries->pluck('relationship', 'column')->toArray();
    }

    /**
     * Set a query to be applied before the search query.
     *
     * @param  callable  $callback
     * @return self
     */
    public function setBeforeQuery(callable $callback): AdvancedSearchContract
    {
        $this->beforeQuery = $callback;

        return $this;
    }

    /**
     * Apply a query before the search query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function applyBeforeQuery($query)
    {
        if (is_callable($this->beforeQuery)) {
            call_user_func($this->beforeQuery, $query, $this->queries, $this->builder);
        }

        return $query;
    }

    /**
     * Set a query to be applied after the search query.
     *
     * @param  callable  $callback
     * @return self
     */
    public function setAfterQuery(callable $callback): AdvancedSearchContract
    {
        $this->afterQuery = $callback;

        return $this;
    }

    /**
     * Apply a query after the search query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function applyAfterQuery($query)
    {
        if (is_callable($this->afterQuery)) {
            call_user_func($this->afterQuery, $query, $this->queries, $this->builder);
        }

        return $query;
    }

    /**
     * Set the search fields prefix.
     *
     * @param  string  $prefix
     * @return self
     */
    public function setPrefix(string $prefix): AdvancedSearchContract
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Get the search fields prefix.
     *
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }
}
