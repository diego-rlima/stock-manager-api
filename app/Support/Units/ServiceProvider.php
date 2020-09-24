<?php

namespace App\Support\Units;

use ReflectionClass;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

abstract class ServiceProvider extends BaseServiceProvider
{
    /**
     * Unit Alias for Translations and Views.
     *
     * @var string
     */
    protected $alias;

    /**
     * List of Unit Service Providers to Register.
     *
     * @var array
     */
    protected $providers = [];

    /**
     * Enable views loading on the Unity.
     *
     * @var bool
     */
    protected $hasViews = false;

    /**
     * Enable translations loading on the Unity.
     *
     * @var bool
     */
    protected $hasTranslations = false;

    /**
     * Bootstrap any application services.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function boot(): void
    {
        $this->registerTranslations();

        $this->registerViews();
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerProviders(collect($this->providers));
    }

    /**
     * Register Unit Custom ServiceProviders.
     *
     * @param  Collection  $providers
     * @return void
     */
    protected function registerProviders(Collection $providers): void
    {
        $providers->each(function ($providerClass) {
            $this->app->register($providerClass);
        });
    }

    /**
     * Register unity translations.
     *
     * @return void
     *
     * @throws \ReflectionException
     */
    protected function registerTranslations(): void
    {
        if (!$this->hasTranslations) {
            return;
        }

        $this->loadTranslationsFrom(
            $this->unitPath('Resources/Lang'),
            $this->alias
        );
    }

    /**
     * Register unity views.
     *
     * @return void
     *
     * @throws \ReflectionException
     */
    protected function registerViews(): void
    {
        if (!$this->hasViews) {
            return;
        }

        $this->loadViewsFrom(
            $this->unitPath('Resources/Views'),
            $this->alias
        );
    }

    /**
     * Detects the unit base path.
     *
     * @param  string|null  $append
     * @return string
     *
     * @throws \ReflectionException
     */
    protected function unitPath(string $append = null): string
    {
        $reflection = new ReflectionClass($this);
        $realPath = realpath(dirname($reflection->getFileName()) . '/../');

        if (!$append) {
            return $realPath;
        }

        return $realPath . '/' . $append;
    }
}
