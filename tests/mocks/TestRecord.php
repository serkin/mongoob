<?php

namespace Mongoob\Record;

class TestRecord extends AbstractRecord
{

    public function collectionName()
    {
        return 'test';
    }

    public function delete()
    {
        return false;
    }
}
