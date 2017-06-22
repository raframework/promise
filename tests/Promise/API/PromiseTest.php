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
            ->withRetryOptions($data['retry_options'])
            ->withDeadline($data['deadline']);

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
                    'retry_options' => [
                        'retries' => 100,
                        'interval' => 3.0,
                    ],
                    'deadline' => time() + 120,
                ]
            ]
        ];
    }
}