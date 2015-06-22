<?php

use Mongoob\Collection;



/**
 * Test on behalf of product collection.
 */
class MongoobCollection extends PHPUnit_Framework_TestCase
{

    public $records;

    public static function setUpBeforeClass()
    {
        \Mongoob\Config::setParam(['db_name' => $GLOBALS['db_name']]);
        require dirname(__DIR__) . '/mocks/TestCollection.php';
        require dirname(__DIR__) . '/mocks/TestRecord.php';

        require dirname(__DIR__) . '/mocks/TestCollection2.php';
        require dirname(__DIR__) . '/mocks/TestRecord2.php';
        require dirname(__DIR__) . '/validators/MongoidValidator.php';

    }

    public function setUp() {

        (new Collection\TestCollection())->getDB()->drop();

        //  Collection\TestCollection();

        $record1 = [
            '_id'   => new \MongoId('111111111111111111111111'),
            'name'  => 'Name 1'
        ];
        $record2 = [
            '_id'   => new \MongoId('111111111111111111111112'),
            'name' => 'Name 2'
        ];

        $collection = new Collection\TestCollection();

        $collection->insert($record1);
        $collection->insert($record2);


        //  Collection\TestCollection2();

        $record1 = [
            '_id'   => new \MongoId('111111111111111111111111'),
            'name'  => 'Name 1'
        ];
        $record2 = [
            '_id'   => new \MongoId('111111111111111111111112'),
            'name' => 'Name 2'
        ];

        $collection = new Collection\TestCollection2();

        $collection->insert($record1);
        $collection->insert($record2);

    }



    public function testErrorOnInsertionWithoutRequiredFields()
    {
        $record = [];

        $collection = new Collection\TestCollection();
        $result = $collection->insert($record);

        $this->assertFalse($result, "Product shouldn't be inserted");
        $this->assertTrue($collection->hasError());
        $this->assertEquals(Collection\AbstractCollection::ERROR_VALIDATION_FAILED, $collection->getErrorInfo()['code']);
    }

    public function testErrorOnInsertionArrayWithExtraKeys()
    {
        $record = [
            'fakefield' => 'fake',
            'name' => 'Makita'
        ];

        $collection = new Collection\TestCollection();
        $result = $collection->insert($record);

        $this->assertFalse($result, "Product shouldn't be inserted");
        $this->assertTrue($collection->hasError());
        $this->assertEquals(Collection\AbstractCollection::ERROR_VALIDATION_FAILED, $collection->getErrorInfo()['code']);

        $collection->validationNeeded(false);
        $collection->insert($record);
        $this->assertFalse($collection->hasError());



    }


    public function testInsertingRecords()
    {

        $record = [
            '_id'   => new \MongoId('111111111111111111111113'),
            'name'  => 'Name 3'
        ];


        $collection = new Collection\TestCollection();

        $collection->insert($record);
        $expected = (new Collection\TestCollection())->countByAttributes([]);

        $this->assertEquals($expected, 3, 'There should be 2 records but '.$expected.' records retrived');
    }


    public function testRecordClassTypeCompliance()
    {
        $elements = iterator_to_array((new Collection\TestCollection())->findAllByAttributes());
        $elements2 = iterator_to_array((new Collection\TestCollection2())->findAllByAttributes());

        $this->assertInstanceOf('\Mongoob\Record\AbstractRecord', $elements[0]);
        $this->assertFalse($elements2[0] instanceof \Mongoob\Record\AbstractRecord);
    }

    public function testRecordExistence()
    {
        $collection = new Collection\TestCollection();
        $result = $collection->existsInCollection(new \MongoId('111111111111111111111111'));

        $this->assertTrue($result);
    }

    public function testRetrivingMultipleRecordsWithQuery()
    {
        $elements = iterator_to_array((new Collection\TestCollection())->findAllByAttributes(['name' => 'Name 1'], ['fields' => ['name']]));

        $this->assertEquals($elements[0]['name'], 'Name 1', 'Retrived array dosnt correspond with given');
    }


    public function testRetrivingOneRecord()
    {
        $elements = (new Collection\TestCollection())->findOneByAttributes(['name' => 'Name 1']);

        $this->assertCount(1, $elements);
    }

    public function testUpdatingRecords()
    {
        $collection = new Collection\TestCollection();

        $vendor = $collection->findOneByAttributes(['name' => 'Name 1']);
        $collection->update(['_id' => $vendor['_id']], ['name' => 'Name 3']);

        $expected = $collection->countByAttributes(['name' => 'Name 3']);

        $this->assertEquals(1, $expected);
    }

    public function testApplyingFilter()
    {

        $record = [
            '_id'   => new \MongoId('111111111111111111111113'),
            'name' => 'Name 3'
        ];

        $collection = new Collection\TestCollection();
        $collection->insert($record);

        $records = iterator_to_array($collection->findAllByAttributes(
            [],
            [
                'fields'    => ['name'],
                'limit'     => ['limit' => 1, 'offset' => 1],
                'sort'      => ['name' => -1]
            ])
        );

        $this->assertCount(1, $records);
        $this->assertEquals($records[0]['name'], 'Name 2');

    }

}
