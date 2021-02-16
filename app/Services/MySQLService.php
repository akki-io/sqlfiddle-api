<?php

namespace App\Services;

use function env;
use function get_class;
use Illuminate\Support\Facades\DB;
use function str_replace;

class MySQLService
{
    const SUPPORTED = [
        'mysql_51',
        'mysql_55',
        'mysql_56',
        'mysql_57',
        'mysql_80',
    ];

    /**
     * Get config DB connection array for all supported.
     *
     * @return array
     */
    public function connections(): array
    {
        $connections = [];

        foreach (self::SUPPORTED as $item) {
            $version = str_replace('mysql_', '', $item);
            $connections[$item] = [
                'driver' => 'mysql',
                'host' => env("MYSQL_{$version}_HOST", $item),
                'port' => env("MYSQL_{$version}_PORT", 3306),
                'database' => '',
                'username' => 'root',
                'password' => 'root',
                'unix_socket' => '',
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'class' => get_class(),
            ];
        }

        return $connections;
    }

    /**
     * Initialize the database.
     *
     * @param $schemaName
     */
    public function _init($schemaName)
    {
        DB::unprepared(DB::raw("CREATE DATABASE {$schemaName}"));
        DB::unprepared(DB::raw("USE {$schemaName}"));
    }

    /**
     * Destroy the database.
     *
     * @param $schemaName
     */
    public function _destroy($schemaName)
    {
        DB::unprepared(DB::raw("DROP DATABASE {$schemaName}"));
    }
}
