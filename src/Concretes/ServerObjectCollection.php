<?php

namespace Nwogu\Setenv\Concretes;

use Closure;
use Iterator;
use ArrayIterator;
use IteratorAggregate;
use Nwogu\Setenv\Concretes\ServerObject;
use Nwogu\Setenv\Contracts\ServerObjectInterface;
use Nwogu\Setenv\Contracts\ServerObjectCollectionInterface;

class ServerObjectCollection implements ServerObjectCollectionInterface, IteratorAggregate
{
    /**
     * @var array $attributes
     */
    protected $attributes = [];

    /**
    * Add server_object to the collection
    *
    * @param ServerObjectInterface $server_object
    * @return void
    */
    public function add(ServerObjectInterface $server_object, string $index): void
    {
        $this->attributes[$index] = $server_object;
    }

    /**
    * Get server_object at specific index
    *
    * @param string $index
    * @return ServerObjectInterface
    */
    public function get(string $index): ?ServerObjectInterface
    {
        return $this->attributes[$index] ?? null;
    }

    /**
    * Return array representation of server_object
    *
    * @return array
    */
    public function toArray(): array
    {
        return $this->attributes;
    }

    /**
     * Map server_objects to array
     * @return array
     */
    public function all()
    {
        $all = [];

        foreach ($this->attributes as $server_object) {
            $all[] = $server_object->toArray();
        }

        return $all;
    }

    /**
     * Count number of items in collection
     * @return int
     */
    public function count(): int
    {
        return count($this->attributes);
    }

    /**
     * Filter the collection
     * @return Nwogu\Setenv\Contracts\ServerObjectCollectionInterface
     */
    public function filter(Closure $callback): ?ServerObjectCollectionInterface
    {
        $filtered = new static;

        foreach ($this->attributes as $index => $server_object) {

            if ($callback($server_object, $index)) {
                $filtered->add($server_object, $index);
            }
        }

        return $filtered;
    }

    /**
     * Pluck values of an attribute from a collection
     * @return array
     */
    public function pluck(string $attribute): array
    {
        $value = [];

        foreach ($this->attributes as $server_object) {
            $value[] = $server_object->get($attribute);
        }

        return array_filter($value);
    }

    /**
     * Retrieve values from the collection where a given attribute matches
     * @param $attribute
     * @param $value
     * @return Nwogu\Setenv\Contracts\ServerObjectCollectionInterface
     */
    public function where($attribute, $value): ServerObjectCollectionInterface
    {
        return $this->filter(function ($object) use ($attribute, $value) {
            return in_array(
                $value, is_array($val = $object->get($attribute)) ? $val : [$val]
            );
        });
    }

    /**
    * @return Iterator
    */
    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->attributes);
    }

}