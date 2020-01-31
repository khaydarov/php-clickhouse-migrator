<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator;

use ClickHouseDB\Client;
use ClickHouseDB\Statement;
use Khaydarovm\Clickhouse\Migrator\Config\Config;
use Khaydarovm\Clickhouse\Migrator\Exceptions\MigrationException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class MigratorTest
 *
 * @package Khaydarovm\Clickhouse\Migrator
 */
class MigratorTest extends TestCase
{
    /**
     * @var Client|MockObject
     */
    private $client;

    /**
     * @var Config|MockObject
     */
    private $config;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = $this->createMock(Client::class);
        $this->config = $this->createMock(Config::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->client = null;
        $this->config = null;
    }

    public function testPreparation(): void
    {
        $this->client
            ->expects($this->once())
            ->method('write');

        $migrator = new Migrator($this->client, $this->config);
        $migrator->prepare();
    }

    /**
     * @throws MigrationException
     */
    public function testMigrateMethod(): void
    {
        $this->expectOutputString('you got here');

        $revision = $this->createMock(Revision::class);
        $revision
            ->method('getRevisionClass')
            ->willReturn('MyMigration');

        $path = __DIR__ . '/migrations/20200131194110_MyMigration.php';

        $migrator = new Migrator($this->client, $this->config);
        $migrator->migrate($path, $revision);
    }

    /**
     * @throws MigrationException
     */
    public function testMigrateException(): void
    {
        $this->expectException(MigrationException::class);

        $revision = $this->createMock(Revision::class);
        $revision
            ->method('getRevisionClass')
            ->willReturn('AnotherMigration');

        $path = __DIR__ . '/migrations/20200131194130_AnotherMigration.php';

        $migrator = new Migrator($this->client, $this->config);
        $migrator->migrate($path, $revision);
    }

    /**
     * @throws MigrationException
     */
    public function testAfterExecutionExceptionCanSave(): void
    {
        $this->expectException(MigrationException::class);
        $this->expectExceptionMessage('Revision error: error');

        $revision = $this->createMock(Revision::class);

        $this->client
            ->expects($this->once())
            ->method('insert')
            ->will($this->throwException(new \Exception('error')));

        $migrator = new Migrator($this->client, $this->config);
        $migrator->afterExecution($revision);
    }

    /**
     * @throws MigrationException
     */
    public function testAfterExecutionExceptionIsError(): void
    {
        $this->expectException(MigrationException::class);
        $this->expectExceptionMessage('Clickhouse statement error');

        $revision = $this->createMock(Revision::class);
        $statement = $this->createMock(Statement::class);

        $this->client
            ->expects($this->once())
            ->method('insert')
            ->willReturn($statement);

        $statement
            ->method('isError')
            ->willReturn(true);

        $migrator = new Migrator($this->client, $this->config);
        $migrator->afterExecution($revision);
    }

    public function testAppliedRevisions(): void
    {
        $statement = $this->createMock(Statement::class);

        $this->client
            ->method('select')
            ->willReturn($statement);

        $array = [
            [
                'version' => '2010',
            ],
            [
                'version' => '2011'
            ]
        ];

        $expect = ['2010', '2011'];

        $statement
            ->method('rows')
            ->willReturn($array);

        $migrator = new Migrator($this->client, $this->config);
        $appliedVersions = $migrator->getAppliedRevisions();

        $this->assertSame($expect, $appliedVersions);
    }
}
