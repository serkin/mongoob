<?php

namespace Mobac\Record;

/**
 * Basic class for all records.
 *
 * @author       Alexander Serkin <serkin.alexander@gmail.com>
 */
abstract class AbstractRecord extends \ArrayObject
{
    use \Mobac\Traits\ErrorHandlerTrait;
    use \Mobac\Traits\DBConnectionTrait;

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
