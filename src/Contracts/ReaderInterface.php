<?php

namespace Nwogu\Setenv\Contracts;

/**
* The Interface provides the contract for the server object readers
*/
interface ReaderInterface 
{
    /**
    * Read in incoming data and parse to server objects
    *
    * @param string $input
    * @return ServerObjectCollectionInterface
    */
    public function read(string $input): ServerObjectCollectionInterface;
}