<?php

namespace App\Support\Domains\Search;

use Illuminate\Support\Facades\Request;
use App\Support\Domains\Search\Contracts\SearchContract;
use App\Support\Domains\Search\Contracts\SimpleSearchContract;

class SimpleSearch implements SimpleSearchContract
{
    /**
     * Columns to search.
     *
     * @var array
     */
    protected array $columns = [];

    /**
     * Variable to get the search value.
     *
     * @var string
     */
    protected string $variable = 's';

    /**
     * The operator to search.
     *
     * @var string
     */
    protected string $operator = 'LIKE';

    /**
     * The operator to search.
     *
     * @var string|null
     */
    protected ?string $term;

    /**
     * The search builder instance.
     *
     * @var \App\Support\Domains\Search\Contracts\SearchContract
     */
    protected Contracts\SearchContract $builder;

    /**
     * SimpleSearch constructor.
     *
     * @param  \App\Support\Domains\Search\Contracts\SearchContract  $instance
     */
    public function __construct(SearchContract $instance)
    {
        $this->builder = $instance;
    }

    /**
     * Apply the search to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function query($query)
    {
        $term = $this->getTerm();

        if (empty($term) || empty($this->getColumns())) {
            return $query;
        }

        $query->where(function ($query) use ($term) {
            foreach ($this->getColumns() as $column) {
                $query->orWhere(
                    $column,
                    $this->getOperator(),
                    $this->builder->termFormatted($term, $column)
                );
            }
        });

        return $query;
    }

    /**
     * Set the term to search.
     *
     * @param  string|null  $term
     * @return self
     */
    public function setTerm(string $term = null): SimpleSearchContract
    {
        if (is_null($term)) {
            $term = Request::get($this->getVariable());
        }

        $this->term = $term;

        return $this;
    }

    /**
     * Get the term.
     *
     * @return string|null
     */
    public function getTerm(): ?string
    {
        return $this->term;
    }

    /**
     * Set the variable to get the search value.
     *
     * @param  string  $variable
     * @return self
     */
    public function setVariable(string $variable): SimpleSearchContract
    {
        $this->variable = $variable;

        return $this;
    }

    /**
     * Get the variable name.
     *
     * @return string
     */
    public function getVariable(): string
    {
        return $this->variable;
    }

    /**
     * Set the operator to search.
     *
     * @param  string  $operator
     * @return self
     */
    public function setOperator(string $operator): SimpleSearchContract
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get the operator.
     *
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * Set the columns to search.
     *
     * @param  array  $columns
     * @return self
     */
    public function setColumns(array $columns): SimpleSearchContract
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Get the columns to search.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }
}
