<?php

namespace App\Http\Controllers;

use App\Http\Requests\FiddleIndexRequest;
use App\Services\AwsLambdaService;
use App\Services\DatabaseService;
use function array_keys;
use function config;
use Exception;
use Illuminate\Support\Facades\DB;
use function is_array;
use function str_replace;

class FiddleController extends Controller
{
    protected $connection;
    protected $schemaName;
    protected $databaseService;

    /**
     * FiddleController constructor.
     */
    public function __construct()
    {
        $this->databaseService = (new DatabaseService());
    }

    /**
     * Perform the fiddle operation.
     *
     * @param \App\Http\Requests\FiddleIndexRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(FiddleIndexRequest $request): \Illuminate\Http\JsonResponse
    {
        $this->connection = $request->input('connection');
        $schemaStatements = $this->getStatements($request->input('schema'));
        $queryStatements = $this->getStatements($request->input('query'));

        if ($schemaStatements && $queryStatements) {
            $this->_init();
            $output = [];

            // execute all schema statements
            if (! is_array($schemaStatements['statements'])) {
                $schemaStatements['statements'] = [$schemaStatements['statements']];
            }
            foreach ($schemaStatements['statements'] as $statement) {
                try {
                    DB::unprepared(DB::raw($statement));
                } catch (Exception $exception) {
                    return $this->respondError($exception->getMessage(), $exception->getCode());
                }
            }

            // execute all query statements
            if (! is_array($queryStatements['statements'])) {
                $queryStatements['statements'] = [$queryStatements['statements']];
            }
            foreach ($queryStatements['statements'] as $statement) {
                try {
                    $result = DB::select(DB::raw($statement));
                    $labels = [];
                    if (is_array($result) && count($result) > 0) {
                        $labels = array_keys((array) $result[0]);
                    }
                    $output[] = [
                        'statement' => $statement,
                        'labels' => $labels,
                        'result' => $result,
                    ];
                } catch (Exception $exception) {
                    return $this->respondError($exception->getMessage(), $exception->getCode());
                }
            }

            $this->_destroy();

            return $this->respondStandard($output);
        }

        return $this->respondError('Unable to process.');
    }

    /**
     * Initialize the db with connection and fresh database.
     */
    private function _init()
    {
        $this->schemaName = $this->databaseService->name();
        config(['database.default' => $this->connection]);

        $this->databaseService->_init($this->connection, $this->schemaName);
    }

    /**
     * Perform destroy action on request complete.
     */
    private function _destroy()
    {
        $this->databaseService->_destroy($this->connection, $this->schemaName);
    }

    /**
     * Get SQL Statements from Lambda.
     *
     * @param $sql
     *
     * @return string[]
     */
    private function getStatements($sql)
    {
        $sql = str_replace(["\n", "\r"], '', $sql);

        return (new AwsLambdaService())->invoke(
            'sqlfiddle-tools-sql-split',
            [
                'statement' => $sql,
            ]
        );
    }
}
