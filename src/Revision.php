<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator;

use Khaydarovm\Clickhouse\Migrator\Exceptions\MigrationException;

/**
 * Class Revision
 *
 * @package App\Clickhouse\Migrator
 */
class Revision
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $filename;

    /**
     * @param string $id
     *
     * @return Revision
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return Revision
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $filename
     *
     * @return Revision
     */
    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @throws MigrationException
     *
     * @return string
     */
    public function getRevisionFile(): string
    {
        if ($this->filename === null) {
            throw new MigrationException('File name is not defined');
        }

        return $this->filename;
    }

    /**
     * @throws MigrationException
     *
     * @return string
     */
    public function getRevisionClass()
    {
        if ($this->name === null) {
            throw new MigrationException('class name is not defined');
        }

        return $this->name;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
