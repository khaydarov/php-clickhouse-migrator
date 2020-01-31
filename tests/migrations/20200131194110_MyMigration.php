<?php
declare(strict_types=1);

use Khaydarovm\Clickhouse\Migrator\AbstractMigration;

/**
 * Class MyMigration
 */
class MyMigration extends AbstractMigration
{
    /**
     * Up Method.
     *
     * This method will be applied on revision migration
     */
    public function up(): void
    {
        echo 'you got here';
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
