<?php

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
        echo 'Hey';
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
