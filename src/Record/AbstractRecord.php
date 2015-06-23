<?php

namespace Mongoob\Record;

/**
 * Basic class for all records.
 *
 * @author       Alexander Serkin <serkin.alexander@gmail.com>
 */
abstract class AbstractRecord extends \ArrayObject
{
    use \Mongoob\Traits\ErrorHandlerTrait;
    use \Mongoob\Traits\DBConnectionTrait;

    const ERROR_CANNOT_DELETE_RECORD = 6;

    /**
     * Deletes record from collection.
     *
     * @return bool
     */
    abstract public function delete();

    /**
     * Get associated collection name.
     *
     * @return string
     */
    abstract public function collectionName();
}
