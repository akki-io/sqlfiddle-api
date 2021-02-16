<?php

namespace App\Services;

use function env;
use function get_class;
use Illuminate\Support\Facades\DB;
use function str_replace;

class PgSQLService
{
    const SUPPORTED = [
        'pgsql_90',
        'pgsql_91',
        'pgsql_92',
        'pgsql_93',
        'pgsql_94',
        'pgsql_95',
        'pgsql_96',
        'pgsql_10',
        'pgsql_11',
        'pgsql_12',
        'pgsql_13',
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
            $version = str_replace('pgsql_', '', $item);
            $connections[$item] = [
                'driver' => 'pgsql',
                'host' => env("PGSQL_{$version}_HOST", $item),
                'port' => env("PGSQL_{$version}_PORT", 5432),
                'database' => 'postgres',
                'username' => 'postgres',
                'password' => 'postgres',
                'charset' => 'utf8',
                'prefix' => '',
                'prefix_indexes' => true,
                'schema' => 'public',
                'sslmode' => 'prefer',
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
        DB::unprepared(DB::raw("CREATE SCHEMA {$schemaName}"));
        DB::unprepared(DB::raw("SET SEARCH_PATH TO {$schemaName}"));
    }

    /**
     * Destroy the database.
     *
     * @param $schemaName
     */
    public function _destroy($schemaName)
    {
        DB::unprepared(DB::raw("DROP SCHEMA {$schemaName} CASCADE"));
    }
}
