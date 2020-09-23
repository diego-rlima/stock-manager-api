<?php

namespace App\Support\Http;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class Resource
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Resource constructor.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Transform a collection of data.
     *
     * @param  mixed        $data
     * @param  string|null  $resource
     * @return mixed
     */
    public function collection($data, string $resource = null)
    {
        $data = $this->fetchResourceCollection($data, $resource)
            ->toResponse($this->request)
            ->getData();

        if (empty($data)) {
            $data = [
                'items' => []
            ];
        }

        return $data;
    }

    /**
     * Transform a single data.
     *
     * @param  mixed        $data
     * @param  string|null  $resource
     * @return mixed
     */
    public function item($data, string $resource = null)
    {
        return $this->fetchResource($data, $resource)
            ->toResponse($this->request)
            ->getData();
    }

    /**
     * Transform a single data.
     *
     * @param  mixed        $data
     * @param  string|null  $resource
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    protected function fetchResource($data, string $resource = null): JsonResource
    {
        $resourceClass = $this->getResourceClass($data, $resource);

        return new $resourceClass($data);
    }

    /**
     * Transform a single data.
     *
     * @param  mixed        $data
     * @param  string|null  $resource
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    protected function fetchResourceCollection($data, string $resource = null): JsonResource
    {
        $resourceClass = $this->getResourceCollectionClass($data, $resource);

        return $resourceClass
            ? new $resourceClass($data)
            : call_user_func([$this->getResourceClass($data), 'collection'], $data);
    }

    /**
     * Transform a single data.
     *
     * @param  mixed        $data
     * @param  string|null  $resource
     * @return string
     */
    protected function getResourceClass($data, string $resource = null): string
    {
        $resource = $resource ?: $this->fetchDefaultResource($data);

        return $resource ?: JsonResource::class;
    }

    /**
     * Transform a single data.
     *
     * @param  mixed        $data
     * @param  string|null  $resource
     * @return string|null
     */
    protected function getResourceCollectionClass($data, string $resource = null): ?string
    {
        $resource = $resource ?: $this->fetchDefaultResourceCollection($data);

        return $resource;
    }

    /**
     * Tries to fetch a default resource for the given data.
     *
     * @param  mixed  $data
     * @return string|null
     */
    protected function fetchDefaultResource($data): ?string
    {
        $modelName = $this->getModelNameFrom($data);

        if ($modelName && $this->hasDefaultResource($modelName)) {
            return call_user_func([$modelName, 'defaultResource']);
        }

        return null;
    }

    /**
     * Tries to fetch a default resource for the given data.
     *
     * @param  mixed  $data
     * @return string|null
     */
    protected function fetchDefaultResourceCollection($data): ?string
    {
        $modelName = $this->getModelNameFrom($data);

        if ($modelName && $this->hasDefaultResourceCollection($modelName)) {
            return call_user_func([$modelName, 'defaultResourceCollection']);
        }

        return null;
    }

    /**
     * Check if the class has a default resource.
     *
     * @param  string  $modelName
     * @return bool
     */
    protected function hasDefaultResource(string $modelName): bool
    {
        return method_exists($modelName, 'defaultResource');
    }

    /**
     * Check if the class has a default collection resource.
     *
     * @param  string  $modelName
     * @return bool
     */
    protected function hasDefaultResourceCollection(string $modelName): bool
    {
        return method_exists($modelName, 'defaultResourceCollection')
            && call_user_func([$modelName, 'defaultResourceCollection']) != null;
    }

    /**
     * Get the model from the given object.
     *
     * @param  mixed  $object
     * @return string|null
     */
    protected function getModelNameFrom($object): ?string
    {
        if (
            $object instanceof LengthAwarePaginator
            || $object instanceof Collection
        ) {
            $class = Arr::first($object);

            if (!is_object($class)) {
                return null;
            }

            return get_class($class);
        }

        if ($object instanceof Model) {
            return get_class($object);
        }

        return null;
    }
}
