<?php

namespace Nwogu\Setenv\Contracts;

/**
* Interface for the Server Object, that is representation of JSON Data
*/
interface ServerObjectInterface
{
    /**
    * Get property value from the server object
    *
    * @param string $property
    * @return mixed
    */
    public function get(string $property);

    /**
    * Return array representation of server object
    *
    * @return array
    */
    public function toArray(): array;
}