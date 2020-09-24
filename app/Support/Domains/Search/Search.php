<?php

namespace App\Support\Domains\Search;

use App\Support\Domains\Search\Contracts\SearchContract;
use App\Support\Domains\Search\Contracts\SimpleSearchContract;
use App\Support\Domains\Search\Contracts\AdvancedSearchContract;

class Search implements SearchContract
{
    /**
     * The SimpleSearch instance.
     *
     * @var SimpleSearchContract
     */
    protected SimpleSearchContract $simple;

    /**
     * The AdvancedSearch instance.
     *
     * @var AdvancedSearchContract
     */
    protected AdvancedSearchContract $advanced;

    /**
     * Use the advanced search.
     *
     * @var bool
     */
    protected bool $useAdvanced = true;

    /**
     * Array with search mutators.
     *
     * @var array
     */
    protected array $mutators = [];

    /**
     * Search constructor.
     *
     * @param  \App\Support\Domains\Search\Contracts\SimpleSearchContract    $simple
     * @param  \App\Support\Domains\Search\Contracts\AdvancedSearchContract  $advanced
     */
    public function __construct(
        SimpleSearchContract $simple,
        AdvancedSearchContract $advanced
    ) {
        $this->setSimple($simple);
        $this->setAdvanced($advanced);
    }

    /**
     * Add a mutator to list.
     *
     * @param  string    $column
     * @param  callable  $function
     * @return void
     */
    public function addMutator(string $column, callable $function): void
    {
        $this->mutators[$column] = $function;
    }

    /**
     * Use the advanced search.
     *
     * @param  bool  $use
     * @return $this
     */
    public function useAdvanced(bool $use = true): self
    {
        $this->useAdvanced = $use;

        return $this;
    }

    /**
     * Retrieves the formatted term.
     *
     * @param  mixed   $term
     * @param  string  $column
     * @param  bool    $formatToLike
     * @return mixed
     */
    public function termFormatted($term, string $column, bool $formatToLike = true)
    {
        if (isset($this->mutators[$column])) {
            $term = call_user_func($this->mutators[$column], $term, $formatToLike);
        } elseif ($formatToLike && !empty($term) && is_string($term)) {
            $term = '%' . $term . '%';
        }

        return $term;
    }

    /**
     * Apply the search query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function query($query)
    {
        $this->simple->query($query);

        if ($this->advanced) {
            $this->advanced->query($query);
        }

        return $query;
    }

    /**
     * Get the mutators array.
     *
     * @return array
     */
    public function getMutators(): array
    {
        return $this->mutators;
    }

    /**
     * Set the simple search instance.
     *
     * @param  \App\Support\Domains\Search\Contracts\SimpleSearchContract  $instance
     * @return \App\Support\Domains\Search\Contracts\SearchContract
     */
    public function setSimple(SimpleSearchContract $instance): SearchContract
    {
        $this->simple = $instance;

        return $this;
    }

    /**
     * Get the simple search instance.
     *
     * @return \App\Support\Domains\Search\Contracts\SimpleSearchContract
     */
    public function getSimple(): SimpleSearchContract
    {
        return $this->simple;
    }

    /**
     * Set the advanced search instance.
     *
     * @param  \App\Support\Domains\Search\Contracts\AdvancedSearchContract  $instance
     * @return \App\Support\Domains\Search\Contracts\SearchContract
     */
    public function setAdvanced(AdvancedSearchContract $instance): SearchContract
    {
        $this->advanced = $instance;

        return $this;
    }

    /**
     * Get the advanced search instance.
     *
     * @return \App\Support\Domains\Search\Contracts\AdvancedSearchContract
     */
    public function getAdvanced(): AdvancedSearchContract
    {
        return $this->advanced;
    }
}
