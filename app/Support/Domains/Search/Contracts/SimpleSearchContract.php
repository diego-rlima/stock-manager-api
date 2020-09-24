<?php

namespace App\Support\Domains\Search\Contracts;

interface SimpleSearchContract
{
    /**
     * Apply the search to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function query($query);

    /**
     * Set the term to search.
     *
     * @param  string|null  $term
     * @return self
     */
    public function setTerm(string $term = null): self;

    /**
     * Get the term.
     *
     * @return string|null
     */
    public function getTerm(): ?string;

    /**
     * Set the variable to get the search value.
     *
     * @param  string  $variable
     * @return self
     */
    public function setVariable(string $variable): self;

    /**
     * Get the variable name.
     *
     * @return string
     */
    public function getVariable(): string;

    /**
     * Set the operator to search.
     *
     * @param  string  $operator
     * @return self
     */
    public function setOperator(string $operator): self;

    /**
     * Get the operator.
     *
     * @return string
     */
    public function getOperator(): string;

    /**
     * Set the columns to search.
     *
     * @param  array  $columns
     * @return self
     */
    public function setColumns(array $columns): self;

    /**
     * Get the columns to search.
     *
     * @return array
     */
    public function getColumns(): array;
}
