<?php

namespace App\Support\Domains\Search\Contracts;

interface SearchContract
{
    /**
     * Use the advanced search.
     *
     * @param  bool  $use
     * @return \App\Support\Domains\Search\Contracts\SearchContract
     */
    public function useAdvanced(bool $use = true): self;

    /**
     * Retrieves the formatted term.
     *
     * @param  mixed   $term
     * @param  string  $column
     * @param  bool    $formatToLike
     * @return mixed
     */
    public function termFormatted($term, string $column, bool $formatToLike = true);

    /**
     * Apply the search query.
     *
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @return mixed
     */
    public function query($query);

    /**
     * Set the simple search instance.
     *
     * @param  \App\Support\Domains\Search\Contracts\SimpleSearchContract  $instance
     * @return \App\Support\Domains\Search\Contracts\SearchContract
     */
    public function setSimple(SimpleSearchContract $instance): self;

    /**
     * Get the simple search instance.
     *
     * @return \App\Support\Domains\Search\Contracts\SimpleSearchContract
     */
    public function getSimple(): SimpleSearchContract;

    /**
     * Set the advanced search instance.
     *
     * @param  \App\Support\Domains\Search\Contracts\AdvancedSearchContract  $instance
     * @return \App\Support\Domains\Search\Contracts\SearchContract
     */
    public function setAdvanced(AdvancedSearchContract $instance): self;

    /**
     * Get the advanced search instance.
     *
     * @return \App\Support\Domains\Search\Contracts\AdvancedSearchContract
     */
    public function getAdvanced(): AdvancedSearchContract;

    /**
     * Add a mutator to list.
     *
     * @param  string    $column
     * @param  callable  $function
     * @return void
     */
    public function addMutator(string $column, callable $function): void;

    /**
     * Get the mutators array.
     *
     * @return array
     */
    public function getMutators(): array;
}
