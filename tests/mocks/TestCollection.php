<?php

namespace Mongoob\Collection;

class TestCollection extends AbstractCollection
{
    public function collectionName()
    {
        return 'test';
    }

    public function recordType()
    {
        return "\Mongoob\Record\TestRecord";
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
