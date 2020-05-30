<?php

namespace Nwogu\Setenv\Contracts;

use Closure;
use Iterator;
use Nwogu\Setenv\Contracts\ServerObjectInterface;

/**
* Interface for The Collection class that contains Server Objects
*/
interface ServerObjectCollectionInterface
{
    /**
    * Add serverObject to the collection
    *
    * @param ServerObjectInterface $serverObject
    * @param string $index
    * @return void
    */
    public function add(ServerObjectInterface $serverObject, string $index): void;

    /**
    * Get serverObject at specific index
    *
    * @param $index
    * @return ServerObjectInterface
    */
    public function get(string $index): ?ServerObjectInterface;

    /**
     * Get the array representation of the collection
     * @return array
     */
    public function toArray(): array;

    /**
     * Filter the collection
     * @return Nwogu\Setenv\Contracts\ServerObjectCollectionInterface
     */
    public function filter(Closure $callback): ?ServerObjectCollectionInterface;

    /**
     * Count number of items in collection
     * @return int
     */
    public function count(): int;

    /**
     * Pluck values of an attribute from a collection
     * @return array
     */
    public function pluck(string $attribute): array;

    /**
     * Retrieve values from the collection where a given attribute matches
     * @param $attribute
     * @param $value
     * @return Nwogu\Setenv\Contracts\ServerObjectCollectionInterface
     */
    public function where($attribute, $value): ServerObjectCollectionInterface;
}