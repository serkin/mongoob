<?php

namespace Mobac;

class Connection
{
    /**
     * @var \MongoDB|null
     */
    private static $instance;

    /**
     * Singleton for MongoDB instance.
     *
     * @return \MongoDB
     *
     * @throws Exception\DBNameNotSpecified
     * @throws Exception\ConnectionNotEstablished
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            $dbName = Config::getParam('db_name');

            if (is_null($dbName)):
                throw new Exception\DBNameNotSpecified();
            endif;

            try {
                self::$instance = (new \MongoClient())->selectDb($dbName);
            } catch (\Exception $e) {
                throw new Exception\ConnectionNotEstablished();
            }
        }

        return self::$instance;
    }

    /**
     * Closes connection.
     */
    public static function close()
    {
        self::$instance = null;
    }
}
