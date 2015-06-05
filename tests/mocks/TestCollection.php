<?php

namespace Mongoob\Collection;
use Mongoob\Record;

class TestCollection extends AbstractCollection
{
    public function collectionName()
    {
        return 'test';
    }

    public function recordType()
    {
        return new Record\TestRecord([]);
    }

    public function collectionShema()
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
