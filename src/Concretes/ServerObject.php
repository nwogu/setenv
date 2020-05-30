<?php

namespace Nwogu\Setenv\Concretes;

use Nwogu\Setenv\Contracts\ServerObjectInterface;

class ServerObject implements ServerObjectInterface
{
    /**
     * @var array $attributes
     */
    protected $attributes;

    /**
     * Initialize server_object
     * @param array $server_object
     */
    public function __construct(array $server_object)
    {
        foreach ($server_object as $key => $value) {
            $this->attributes[$key] = $value;
        }
    }

    /**
    * Get property value from the server_object
    *
    * @param string $property
    * @return mixed
    */
    public function get(string $property)
    {
        return $this->attributes[$property] ?? null;
    }

    public function __get($property)
    {
        return $this->get($property);
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
}