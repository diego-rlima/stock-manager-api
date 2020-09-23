<?php

namespace App\Support\Http;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class Parameters
{
    /**
     * HTTP Request.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Create a new class instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get order parameter.
     *
     * @param  string  $default
     * @return string
     */
    public function order(string $default = 'desc'): string
    {
        return $this->get('order', $default) == 'desc' ? 'desc' : 'asc';
    }

    /**
     * Get sort parameter.
     *
     * @param  string  $default
     * @param  array   $available
     * @return string
     */
    public function sort(string $default = 'id', array $available = []): string
    {
        $sort = $this->get('sort', $default);

        if (!empty($available) && !in_array($sort, $available)) {
            $sort = $default;
        }

        return $sort;
    }

    /**
     * Get limit parameter.
     *
     * @param  int       $default
     * @param  int|null  $max
     * @return int
     */
    public function limit(int $default = 20, int $max = null): int
    {
        $limit = intval($this->get('limit', $default));

        if ($max && $limit > $max) {
            return $max;
        }

        return $limit;
    }

    /**
     * Get search parameter.
     *
     * @param  string|null  $default
     * @return string|null
     */
    public function search(string $default = null): ?string
    {
        return $this->get('search', $default);
    }

    /**
     * Get query parameter.
     *
     * @param  string  $name
     * @param  mixed   $default
     * @param  bool    $isArray
     * @return mixed
     */
    public function get(string $name, $default = null, bool $isArray = false)
    {
        $value = $this->request->query($name) ?? $default;

        if ($isArray) {
            $value = Arr::wrap($value);
        } elseif (!is_array($default) && is_array($value)) {
            $value = Arr::first($value);
        }

        return $value;
    }
}
