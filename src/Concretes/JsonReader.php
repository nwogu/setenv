<?php

namespace Nwogu\Setenv\Concretes;

use Nwogu\Setenv\Concretes\ServerObject;
use Nwogu\Setenv\Contracts\ReaderInterface;
use Nwogu\Setenv\Concretes\ServerObjectCollection;
use Nwogu\Setenv\Contracts\ServerObjectCollectionInterface;


class JsonReader implements ReaderInterface
{
    /**
    * Read in incoming data and parse to objects
    *
    * @param string $input
    * @return ServerObjectCollectionInterface
    */
    public function read(string $input): ServerObjectCollectionInterface
    {
        $data = json_decode($input, true);
        $server_object_collection = new ServerObjectCollection;

        foreach ($data as $name => $server_object) {
            $server_object_collection->add(new ServerObject($server_object), $name);
        }

        return $server_object_collection;
    }

    /**
     * Create new instance of class
     * @return self
     */
    public static function create(): self
    {
        return new static;
    }
}