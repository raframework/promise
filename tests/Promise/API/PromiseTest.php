<?php

/**
 * User: coderd
 * Date: 2017/6/21
 * Time: 16:50
 */

namespace tests\Promise\API;


use tests\TestBase;
use Ramsey\Uuid\Uuid;
use Promise\API\Promise;
use Promise\Model\Page\PageFactory;

class PromiseTest extends TestBase
{

    public function testSendHTTPRequest()
    {
        $promise = new Promise('promise');
        $options = [
            'headers' => [
                'X-Request-Id' => Uuid::uuid4(),
            ]
        ];
        $promise->sendHTTPRequest('GET', 'http://localhost:12345/json', $options)

            ->expectStatusCode(200)
            ->expectBody('{"array":{"int":1}}')
            ->expectBodyJsonHasKey('array.int')
            ->expectBodyContainsString('int')
            ->expectBodyJsonFieldEquals('array.int', 1)

            ->withDeadline(time() + 3600)
            ->withMaxRetries(3)
            ->withRetryInterval(3);

        $this->assertTrue($promise->doNow());
    }

    /**
     * @depends testSendHTTPRequest
     */
    public function testHandleTasks()
    {
        $this->assertTrue(PageFactory::i()->taskManager->handleTasks());
    }

    /**
     * @expectedException \Promise\Lib\Exception\InvalidArgumentException
     * @expectedExceptionMessage "Expected response" must be set
     */
    public function testSendHTTPRequestWithoutExpectedResponse()
    {
        $promise = new Promise('test');
        $promise->sendHTTPRequest('GET', 'http://localhost:12345');

        $this->assertTrue($promise->doNow());
    }

    /**
     * @expectedException \Promise\Lib\Exception\InvalidArgumentException
     * @expectedExceptionMessage "Deadline" must be set
     */
    public function testSendHTTPRequestWithoutDeadline()
    {
        $promise = new Promise('test');
        $promise->sendHTTPRequest('GET', 'http://localhost:12345')
            ->expectStatusCode(200);

        $this->assertTrue($promise->doNow());
    }

    /**
     * @expectedException \Promise\Lib\Exception\InvalidArgumentException
     * @expectedExceptionMessage "Max retries" must be set
     */
    public function testSendHTTPRequestWithoutMaxRetries()
    {
        $promise = new Promise('test');
        $promise->sendHTTPRequest('GET', 'http://localhost:12345')
            ->expectStatusCode(200)
            ->withDeadline(time() + 200);

        $this->assertTrue($promise->doNow());
    }

    /**
     * @expectedException \Promise\Lib\Exception\InvalidArgumentException
     * @expectedExceptionMessage "Retry interval" must be set
     */
    public function testSendHTTPRequestWithoutRetryInterval()
    {
        $promise = new Promise('test');
        $promise->sendHTTPRequest('GET', 'http://localhost:12345')
            ->expectStatusCode(200)
            ->withDeadline(time() + 200)
            ->withMaxRetries(20);

        $this->assertTrue($promise->doNow());
    }
}