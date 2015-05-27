<?php

use Mongoob\Collection;

require dirname(__DIR__) . '/mocks/TestCollection.php';
require dirname(__DIR__) . '/mocks/TestRecord.php';

/**
 * Test on behalf of product collection.
 */
class Mongoob_Collection extends PHPUnit_Framework_TestCase
{
    
    public $records;

    public static function setUpBeforeClass()
    {
        \Mongoob\Config::setParam(['db_name' => $GLOBALS['db_name']]);

    }
    
    public function setUp() {

        (new Collection\TestCollection())->getDB()->drop();

        $record1 = [
            'name' => 'Name 1'
            ];
        $record2 = [
            'name' => 'Name 2'
            ];

        $collection = new Collection\TestCollection();

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
    }

    public function testInsertingRecords()
    {

        $record1 = [
            'name' => 'Name 3'
            ];
        $record2 = [
            'name' => 'Name 4'
            ];

        $collection = new Collection\TestCollection();

        $collection->insert($record1);
        $collection->insert($record2);

        $expected = (new Collection\TestCollection())->countByAttributes([]);

        $this->assertEquals($expected, 4, 'There should be 4 records but '.$expected.' records retrived');
    }

    /**
     * @depends testInsertingRecords
     */
    public function testRetrivingMultipleRecordsWithQuery()
    {
        $elements = iterator_to_array((new Collection\TestCollection())->findAllByAttributes(['name' => 'Name 1'], ['fields' => ['name']]));

        $this->assertEquals($elements[0]['name'], 'Name 1', 'Retrived array dosnt correspond with given');
    }

    /**
     * @depends testInsertingRecords
     */
    public function testRetrivingMultipleRecords()
    {
        $elements = iterator_to_array((new Collection\TestCollection())->findAllByAttributes([]));

        $this->assertEquals(count($elements), 2, 'There should be 2 records but '.count($elements).' records retrived');
    }

    /**
     * @depends testInsertingRecords
     */
    public function testRetrivingOneRecord()
    {
        $elements = (new Collection\TestCollection())->findOneByAttributes([]);

        $this->assertTrue(!empty($elements), 'There should be only 1 record');
    }

    /**
     * @depends testRetrivingMultipleRecordsWithQuery
     */
    public function testUpdatingRecords()
    {
        $vendorCollection = new Collection\TestCollection();

        $vendor = $vendorCollection->findOneByAttributes(['name' => 'Name 1']);
        $vendorCollection->update(['_id' => $vendor['_id']], ['name' => 'NoName']);

        $updatedVendor = $vendorCollection->findOneByAttributes(['name' => 'NoName'], ['fields' => ['name']]);

        $this->assertEquals($updatedVendor['name'], 'NoName', "New field <name.en = Mokita> wasn't set");
    }

    public static function tearDownAfterClass()
    {
        return true;
    }
}
