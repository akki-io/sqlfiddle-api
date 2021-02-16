<?php

namespace App\Services;

use Aws\Lambda\LambdaClient;
use function config;
use function dd;
use function json_decode;
use function json_encode;

class AwsLambdaService
{
    protected $client;

    /**
     * AwsLambdaService constructor.
     */
    public function __construct()
    {
        $this->client = new LambdaClient([
            'version' => 'latest',
            'region'  => config('aws.region'),
        ]);
    }

    /**
     * Invoke a Lambda function.
     *
     * @param $functionName
     * @param null $payload
     *
     * @return false|string[]
     */
    public function invoke($functionName, $payload = [])
    {
        $result = $this->client->invoke([
            'FunctionName' => $functionName,
            'Payload' => json_encode($payload),
            'Qualifier' => '1',
        ]);

        if ($result->get('FunctionError')) {
            return false;
        }

        return json_decode((string) $result->get('Payload'), true);
    }
}
