<?php

/**
 * User: coderd
 * Date: 2017/6/21
 * Time: 16:50
 */

namespace tests\Promise\API;


use tests\TestBase;
use Promise\API\Promise;

class PromiseTest extends TestBase
{

    /**
     * @dataProvider dataProvider
     */
    public function testSendHTTPRequest($data)
    {
        $promise = new Promise($data['app_key']);
        $promise->sendHTTPRequest($data['request']['method'], $data['request']['uri'],$data['request']['options'])
            ->expectStatusCode($data['expected_response']['status_code'])
            ->withDeadline($data['deadline'])
            ->withMaxRetries($data['max_retries'])
            ->withRetryInterval($data['retry_interval']);

        $this->assertTrue($promise->doNow());
    }

    public function dataProvider()
    {
        return [
            [
                [
                    'app_key' => 'promise',
                    'request' => [
                        'method' => 'GET',
                        'uri' => 'http://localhost',
                        'options' => [],
                    ],
                    'expected_response' => [
                        'status_code' => 200,
                    ],
                    'max_retries' => 100,
                    'retry_interval' => 3000,
                    'deadline' => time() + 120,
                ]
            ]
        ];
    }
}