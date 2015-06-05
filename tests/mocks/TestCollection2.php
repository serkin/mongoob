<?php

namespace Mongoob\Collection;
use Mongoob\Record;

class TestCollection2 extends AbstractCollection
{
    public function collectionName()
    {
        return 'test2';
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
