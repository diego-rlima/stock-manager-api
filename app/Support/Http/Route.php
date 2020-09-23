<?php

namespace App\Support\Http;

abstract class Route
{
    /**
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * @var array
     */
    protected $options;

    /**
     * Create a new class instance.
     *
     * @param  array  $options
     */
    public function __construct(array $options)
    {
        $this->router = app('router');
        $this->options = $options;
    }

    /**
     * Register Routes.
     *
     * @return void
     */
    public function register(): void
    {
        $this->router->group($this->options, function () {
            $this->routes();
        });
    }

    /**
     * Create a new route instance.
     *
     * @param  array  $options
     * @return static
     */
    public static function make(array $options): self
    {
        return new static($options);
    }

    /**
     * Define the routes.
     *
     * @return void
     */
    abstract public function routes(): void;
}
