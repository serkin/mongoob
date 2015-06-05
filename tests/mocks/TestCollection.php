<?php

namespace Mongoob\Collection;
use Mongoob\Record;

class TestCollection extends AbstractCollection
{
    public function collectionName()
    {
        return 'test';
    }

    public function recordClass()
    {
        return new Record\TestRecord([]);
    }

    public function collectionSchema()
    {
        return [
            'root' => [
                '_id' => [
                    '_type' => 'mongoid'
                ],
                'name' => [
                    '_type' => 'required_string'
                ],
            ]
        ];
    }
}
