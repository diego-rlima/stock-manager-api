<?php

namespace App\Support\Domains\Search\Contracts;

interface AdvancedSearchContract
{
    /**
     * Apply the search query.
     *
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function query($query);

    /**
     * Add a column to be searched.
     *
     * @param  string       $name
     * @param  string|null  $column
     * @param  string       $operator
     * @return self
     */
    public function addColumn(string $name, string $column = null, string $operator = 'LIKE'): self;

    /**
     * Get the columns to search.
     *
     * @return array
     */
    public function getColumns(): array;

    /**
     * Add a relationship to be searched.
     *
     * @param  string    $name
     * @param  string    $relationship
     * @param  callable  $callback
     * @return self
     */
    public function addRelationship(string $name, string $relationship, callable $callback): self;

    /**
     * Get the relationships.
     *
     * @return array|null
     */
    public function getRelationships(): ?array;

    /**
     * Set a query to be applied before the search query.
     *
     * @param  callable  $callback
     * @return self
     */
    public function setBeforeQuery(callable $callback): self;

    /**
     * Apply a query before the search query.
     *
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function applyBeforeQuery($query);

    /**
     * Set a query to be applied after the search query.
     *
     * @param  callable  $callback
     * @return self
     */
    public function setAfterQuery(callable $callback): self;

    /**
     * Apply a query after the search query.
     *
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function applyAfterQuery($query);

    /**
     * Set the search fields prefix.
     *
     * @param  string  $prefix
     * @return self
     */
    public function setPrefix(string $prefix): self;

    /**
     * Get the search fields prefix.
     *
     * @return string
     */
    public function getPrefix(): string;
}
