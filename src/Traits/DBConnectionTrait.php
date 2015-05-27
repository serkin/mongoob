<?php

namespace Mongoob\Traits;

trait DBConnectionTrait
{
    /**
     * Gets MongoDB instance.
     *
     * @return \MongoDB
     */
    public function getDB()
    {
        return \Mongoob\Connection::getInstance();
    }
}
