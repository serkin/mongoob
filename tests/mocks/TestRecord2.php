<?php

namespace Mongoob\Record;

class TestRecord2 extends AbstractRecord
{

    public function collectionName()
    {
        return 'test2';
    }

    public function delete()
    {
        return false;
    }
}
