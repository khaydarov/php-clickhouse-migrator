<?php
declare(strict_types=1);

use Khaydarovm\Clickhouse\Migrator\AbstractMigration;

/**
 * Class AnotherMigration
 */
class AnotherMigration extends AbstractMigration
{
    /**
     * Up Method.
     *
     * This method will be applied on revision migration
     */
    public function up(): void
    {
        throw new Exception();
    }

    /**
     * Down Method.
     *
     * This method will be applied on revision rollback
     */
    public function down(): void
    {
    }
}
