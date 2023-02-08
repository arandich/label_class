<?php

namespace Test\Persistence;

use PDO;
use PHPUnit\DbUnit\Operation\Factory;
use PHPUnit\DbUnit\TestCase;
use Services\LabelService;

class LabelServiceTest extends TestCase
{
    private $pdo;
    private $conn = null;
    private static LabelService $label;
    protected function getConnection()
    {
        if ($this->conn === null) {
            $this->pdo = new PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWORD']);
            $this->conn = $this->createDefaultDBConnection($this->pdo, $GLOBALS['DB_DBNAME']);
        }

        return $this->conn;
    }

    protected function getDataSet()
    {
        return $this->createFlatXMLDataSet('tests/Fixtures/label-test.xml');
    }

    public function setUp(): void
    {
        $this->clearDb();
        parent::setUp();
        self::$label = new LabelService($this->pdo);

        $this->getDatabaseTester()->setSetUpOperation($this->getSetUpOperation());
        $this->getDatabaseTester()->setDataSet($this->getDataSet());
        $this->getDatabaseTester()->onSetUp();
    }

    protected function getTearDownOperation()
    {
        return Factory::DELETE_ALL();
    }

    protected function clearDb()
    {
        $aTableNames = $this->getConnection()->createDataSet()->getTableNames();
        $aTableNames = array_values($aTableNames);

        $op = Factory::DELETE_ALL();
        $op->execute($this->getConnection(), $this->getConnection()->createDataSet($aTableNames));
    }

    public function testTables()
    {
        $queryTable = $this->getConnection()->createQueryTable('user_account', 'SELECT * FROM user_account');
        $expectedTable = $this->getDataSet()->getTable("user_account");
        $this->assertTablesEqual($expectedTable, $queryTable);
    }

    public function testReplaceEntityLabels()
    {
        $this->assertEquals(true, self::$label->replaceEntityLabels('user_account', 1, ['label1']));
        $this->assertEquals(true, self::$label->replaceEntityLabels('company', 1, ['label1']));
        $this->assertEquals(true, self::$label->replaceEntityLabels('site', 1, ['label1']));
    }

    public function testAddLabelsToEntity()
    {
        $this->assertEquals(true, self::$label->addLabelsToEntity('user_account', 2, ['new_member']));
        $this->assertEquals(true, self::$label->addLabelsToEntity('company', 2, ['new_member']));
        $this->assertEquals(true, self::$label->addLabelsToEntity('site', 2, ['new_member']));
    }

    public function testDeleteEntityLabels()
    {
        $this->assertEquals(true, self::$label->deleteEntityLabels('user_account', 3, ['label2']));
        $this->assertEquals(true, self::$label->deleteEntityLabels('company', 3, ['label2']));
        $this->assertEquals(true, self::$label->deleteEntityLabels('site', 3, ['label2']));
    }

    public function testGetEntityLabels()
    {
        $this->assertEquals(['label1','label2'], self::$label->getEntityLabels('user_account', 4));
        $this->assertEquals(['label1','label2'], self::$label->getEntityLabels('company', 4));
        $this->assertEquals(['label1','label2'], self::$label->getEntityLabels('site', 4));
    }
}
