<?php

namespace App\Services;

use function env;
use function get_class;
use Illuminate\Support\Facades\DB;
use function str_replace;

class MsSQLService
{
    const SUPPORTED = [
        'mssql_2017',
        'mssql_2019',
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
            $version = str_replace('mssql_', '', $item);
            $connections[$item] = [
                'driver' => 'sqlsrv',
                'host' => env("MSSQL_{$version}_HOST", $item),
                'port' => env("MSSQL_{$version}_PORT", 1433),
                'database' => 'master',
                'username' => 'sa',
                'password' => 'StrongPassword@12345',
                'charset' => 'utf8',
                'prefix' => '',
                'prefix_indexes' => true,
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
        DB::unprepared(DB::raw('USE master'));
        DB::unprepared(DB::raw("DROP DATABASE {$schemaName}"));
    }
}
