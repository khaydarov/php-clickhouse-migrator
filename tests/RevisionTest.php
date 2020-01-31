<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator;

use Khaydarovm\Clickhouse\Migrator\Exceptions\MigrationException;
use PHPUnit\Framework\TestCase;

class RevisionTest extends TestCase
{
    /**
     * @throws MigrationException
     */
    public function testRevisionSetUp()
    {
        $id = '10';
        $name = 'conf';
        $filename = 'config.php';

        $revision = new Revision();
        $revision
            ->setId($id)
            ->setFilename($filename)
            ->setName($name);

        $this->assertSame($revision->getId(), $id);
        $this->assertSame($revision->getRevisionFile(), $filename);
        $this->assertSame($revision->getRevisionClass(), $name);
    }

    /**
     * @throws MigrationException
     */
    public function testRevisionDefaultFilename()
    {
        $this->expectException(MigrationException::class);
        $this->expectErrorMessage('File name is not defined');

        $id = '11';
        $revision = new Revision();
        $revision
            ->setId($id);

        $this->assertSame($revision->getId(), $id);
        $this->assertSame($revision->getRevisionFile(), null);
    }

    /**
     * @throws MigrationException
     */
    public function testRevisionDefaultClassname()
    {
        $this->expectException(MigrationException::class);
        $this->expectErrorMessage('class name is not defined');

        $id = '11';
        $revision = new Revision();
        $revision
            ->setId($id);

        $this->assertSame($revision->getId(), $id);
        $this->assertSame($revision->getRevisionClass(), null);
    }
}
