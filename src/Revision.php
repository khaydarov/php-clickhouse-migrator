<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator;

/**
 * Class Revision
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
     * @return Revision
     */
    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return string
     */
    public function getRevisionFile(): string
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getRevisionClass()
    {
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