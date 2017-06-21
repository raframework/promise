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
    public function testHTTP()
    {
        $promise = new Promise('promise');
        $promise->sendHTTPRequest('GET', 'http://localhost')
            ->expectStatusCode(200);

        $promise->doNow();
    }
}