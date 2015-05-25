<?php

namespace Mobac\Traits;

trait DBConnectionTrait
{
    /**
     * Gets MongoDB instance.
     *
     * @return \MongoDB
     */
    public function getDB()
    {
        return \Mobac\Connection::getInstance();
    }
}
