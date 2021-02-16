<?php

namespace Tests\Unit;

use App\Services\MySQLService;
use App\Services\PgSQLService;
use Tests\TestCase;

class FiddleControllerTest extends TestCase
{
    /** @test */
    public function test_mysql_databases()
    {
        $mysqlConnections = MySQLService::SUPPORTED;

        foreach ($mysqlConnections as $connection) {
            $this->testResult($connection);
        }
    }

    /** @test */
    public function test_pgsql_databases()
    {
        $mysqlConnections = PgSQLService::SUPPORTED;

        foreach ($mysqlConnections as $connection) {
            $this->testResult($connection);
        }
    }

    /**
     * Test the result.
     *
     * @param $connection
     */
    private function testResult($connection)
    {
        $this->post('/fiddle', [
            'connection' => $connection,
            'schema' => 'CREATE TABLE test (id INT); INSERT INTO test (id) VALUES (1), (2);',
            'query' => 'SELECT * FROM test;',
        ])->assertSuccessful()
        ->assertJson([
            'data' => [
                [
                    'statement' => 'SELECT * FROM test',
                    'labels' => ['id'],
                    'result' => [
                        ['id' => 1],
                        ['id' => 2],
                    ],
                ],
            ],
            'success' => true,
            'status_code' => 200,
        ]);
    }
}
