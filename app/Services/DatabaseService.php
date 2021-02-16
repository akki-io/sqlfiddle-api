<?php

namespace App\Services;

use function config;

class DatabaseService
{
    /**
     * Generate a random string.
     *
     * @param int $length
     *
     * @return false|string
     */
    public function name($length = 16)
    {
        return substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'), 0, $length);
    }

    /**
     * Get the connections from all supports databases.
     *
     * @return array
     */
    public function connections(): array
    {
        return (new MySQLService())->connections()
            + (new PgSQLService())->connections();
    }

    /**
     * Initialize the database.
     *
     * @param $connection
     * @param $schemaName
     */
    public function _init($connection, $schemaName)
    {
        $class = config("database.connections.{$connection}.class");

        (new $class)->_init($schemaName);
    }

    /**
     * Destroy the database.
     *
     * @param $connection
     * @param $schemaName
     */
    public function _destroy($connection, $schemaName)
    {
        $class = config("database.connections.{$connection}.class");

        (new $class)->_destroy($schemaName);
    }
}
