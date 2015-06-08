<?php

namespace Mongoob\Collection;

/**
 * Basic class for all collections.
 *
 * @author       Alexander Serkin <serkin.alexander@gmail.com>
 */
abstract class AbstractCollection
{
    use \Mongoob\Traits\ErrorHandlerTrait;
    use \Mongoob\Traits\DBConnectionTrait;

    const ERROR_DUPLICATE_RECORD = 1;
    const ERROR_CANNOT_INSERT_RECORD = 2;
    const ERROR_VALIDATION_FAILED = 5;

    /**
     * Gets record type
     *
     * @return string|null
     */
    public function recordClass()
    {
        return null;
    }

    /**
     * Get associated collection name.
     *
     * @return string
     */
    abstract public function collectionName();

    /**
     * Returns schema of collection's record.
     *
     * @return array
     */
    abstract public function collectionSchema();

    /**
     * Return records from collection according given conditions.
     *
     * @param array $query   Array of conditions
     * @param array $options Array of options which can impose some extra conditions
     *
     * @see http://php.net/manual/en/mongocollection.find.php
     *
     * @return \Generator
     */
    public function findAllByAttributes($query = [], $options = [])
    {
        $cursor = $this->getDB()->{$this->collectionName()}->find(
                $query,
                !empty($options['fields']) ? $options['fields'] : ['_id']);

        $this->applyOptionsToMongoCursor($cursor, $options);

        return $this->instantiateRecordClass($cursor);
    }

    /**
     * Returns one record from collection according given conditions.
     *
     * @param array $query   Array of conditions
     * @param array $options Array of options which can impose some extra conditions
     *
     * @return array
     */
    public function findOneByAttributes($query = [], $options = [])
    {
        $options['limit']['limit'] = 1;

        $returnValue = [];

        foreach ($this->findAllByAttributes($query, $options) as $element):
            $returnValue = $element;
        break;
        endforeach;

        return $returnValue;
    }

    /**
     * Count records in collection according conditions.
     *
     * @param array $query Array of conditions
     *
     * @see http://php.net/manual/en/mongocursor.count.php
     *
     * @return int
     */
    public function countByAttributes($query = [])
    {
        return $this->getDB()->{$this->collectionName()}->count($query);
    }

    /**
     * Adds new row in collection
     * Return array like ['_id' => newId] if success or empty array.
     *
     * @param array $arr Array of values
     *
     * @see http://php.net/manual/en/mongocollection.insert.php
     *
     * @return array|bool Array containing ['_id' => newId] or false
     */
    public function insert($arr)
    {
        $this->clearError();

        $isArrayValid = $this->validate($arr);

        if ($isArrayValid) {
            $result = $this->getDB()->{$this->collectionName()}->insert($arr);

            if (empty($result['code'])) {
                $returnValue = ['_id' => $arr['_id']];
            } else {
                $this->setError(self::ERROR_CANNOT_INSERT_RECORD);
            }
        }

        return !empty($returnValue) ? $returnValue : false;
    }

    /**
     * Updates row in collection.
     *
     * @param array  $query   Query criteria for the documents to update.
     * @param array  $arr     Replacement document
     * @param string $action  Action name. Defaut '$set' can be '$unset'
     * @param array  $options An array of options for the update operation
     *
     * @see http://www.php.net/manual/en/mongocollection.update.php
     *
     * @return bool
     */
    public function update($query, $arr, $action = '$set', $options = [])
    {
        $this->clearError();

        if ($action != '$set') {
            $isArrayValid = true;
        } else {
            $isArrayValid = $this->validate($arr, false);
        }

        if ($isArrayValid) {
            $result = $this->getDB()->{$this->collectionName()}->update($query, [$action => $arr], $options);

            if (!empty($result['code'])) {
                $this->setError(self::ERROR_CANNOT_INSERT_RECORD);
            }
        }

        return (!$this->hasError()) ? true : false;
    }

    /**
     * Checks if record exists in collection.
     *
     * @param \MongoId $idRecord
     *
     * @return bool
     */
    public function existsInCollection(\MongoId $idRecord)
    {
        return  (bool)$this->findOneByAttributes(['_id' => $idRecord]);
    }

    /**
     * Applies options to cursor.
     *
     * @param \MongoCursor $cursor
     * @param array        $options
     *
     * @return \MongoCursor
     */
    private function applyOptionsToMongoCursor(\MongoCursor&$cursor, $options = [])
    {
        if (isset($options['limit']['limit'])):
            $cursor->limit($options['limit']['limit']);
        endif;

        if (isset($options['limit']['offset'])):
            $cursor->skip($options['limit']['offset']);
        endif;

        if (isset($options['sort'])):
            $cursor->sort($options['sort']);
        endif;

        return $cursor;
    }

    /**
     * Assign every retrived record with collection's recordClass.
     *
     * @param \MongoCursor $cursor
     */
    private function instantiateRecordClass(\MongoCursor $cursor)
    {
        $recordClass = $this->recordClass();

        switch ($recordClass) {
            case null:
                while ($cursor->hasNext()):
                    yield $cursor->getNext();
                endwhile;
            break;

            default:
                while ($cursor->hasNext()):
                    yield new $recordClass($cursor->getNext());
                endwhile;
            break;
        }
    }



    /**
     * Validates array against schema.
     *
     * Sets error if validation fails.
     *
     *
     * @param array $arr
     * @param bool  $requiredMode
     *
     * @return bool
     */
    private function validate($arr, $requiredMode = true)
    {
        $schema = $this->collectionSchema();
        $validator = new \Volan\Volan($schema);
        $validator->setRequiredMode($requiredMode);
        $result = $validator->validate($arr);

        if ($result === false) {
            $error = $validator->getErrorInfo();
            $this->setError(self::ERROR_VALIDATION_FAILED, $error['error']);
        }

        return $result;
    }
}
