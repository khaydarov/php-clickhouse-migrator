<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator;

use Khaydarovm\Clickhouse\Migrator\Config\Config;
use Khaydarovm\Clickhouse\Migrator\Config\ConfigManager;
use Khaydarovm\Clickhouse\Migrator\Exceptions\ConfigException;
use Khaydarovm\Clickhouse\Migrator\Exceptions\MigrationException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ManagerTest extends TestCase
{
    /**
     * @var ConfigManager|MockObject
     */
    private $mock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mock = $this->createMock(ConfigManager::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->mock = null;
    }

    /**
     * @throws Exceptions\ConfigException
     */
    public function testGetConfigMethod(): void
    {
        $this->mock->expects($this->once())
            ->method('getConfig');

        $manager = new Manager($this->mock);
        $manager->getConfig();
    }

    /**
     * @throws ConfigException
     */
    public function testGetConfigException(): void
    {
        $this->expectException(ConfigException::class);
        $this->mock->expects($this->once())
            ->method('getConfig')
            ->will($this->throwException(new ConfigException()));

        $manager = new Manager($this->mock);
        $manager->getConfig('space');
    }

    /**
     * @throws Exceptions\MigrationException
     */
    public function testGetFoundRevisionById(): void
    {
        $manager = new Manager($this->mock);

        $path = __DIR__ . '/migrations';

        $revisionId = '20200131194130';
        $revision = $manager->getRevisionById($path, $revisionId);
        $this->assertSame($revisionId, $revision->getId());

        $revisionId = '20200131194110';
        $revision = $manager->getRevisionById($path, $revisionId);
        $this->assertSame($revisionId, $revision->getId());

        $this->expectException(MigrationException::class);
        $this->expectExceptionMessage('Path is not directory');
        $manager->getRevisionById('dfsd.hpp', $revisionId);
    }

    /**
     * @throws MigrationException
     */
    public function testGetRevisionByIdNotFound(): void
    {
        $manager = new Manager($this->mock);
        $path = __DIR__ . '/migrations';

        $this->expectException(MigrationException::class);
        $this->expectExceptionMessage('Revision not found');
        $manager->getRevisionById($path, '11');
    }

    /**
     * @throws MigrationException
     */
    public function testGetAvailableRevisions(): void
    {
        $manager = new Manager($this->mock);
        $path = __DIR__ . '/migrations';

        $applied = [];
        $available = $manager->getAvailableRevisions($path, $applied);

        $expected = [
            '20200131194110',
            '20200131194130'
        ];

        $index = 0;
        foreach ($available as $revision) {
            /** @var Revision $revision */
            $id = $revision->getId();
            $this->assertSame($expected[$index], $id);
            $index++;
        }

        $applied = [
            '20200131194130'
        ];

        $expected = [
            '20200131194110'
        ];

        $available = $manager->getAvailableRevisions($path, $applied);
        $index = 0;
        foreach ($available as $revision) {
            /** @var Revision $revision */
            $id = $revision->getId();
            $this->assertSame($expected[$index], $id);
            $index++;
        }

        $this->expectException(MigrationException::class);
        $this->expectExceptionMessage('Path is not directory');
        $manager->getAvailableRevisions('ffff.php', []);
    }

    /**
     * @throws ConfigException
     */
    public function testManagerGetConfig(): void
    {
        $this->mock
            ->expects($this->once())
            ->method('getConfig');

        $manager = new Manager($this->mock);
        $config = $manager->getConfig();

        $this->assertInstanceOf(Config::class, $config);
    }

    public function testManagerGetConfigPath(): void
    {
        $this->mock
            ->expects($this->once())
            ->method('getMigrationsPath');

        $manager = new Manager($this->mock);
        $manager->getMigrationsPath();
    }
}
