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
use PhpAmqpLib\Connection\AMQPStreamConnection;

class PromiseTest extends TestBase
{
    const AMQP_EXCHANGE = 'promise_unit_test';

    public function setUp()
    {
        parent::setUp();

        // Initialize AMQP exchange
        $this->initializeAMQP();
    }

    private function initializeAMQP()
    {
        $connection = new AMQPStreamConnection(
            '127.0.0.1',
            '5672',
            'guest',
            'guest',
            '/'
        );
        $channel = $connection->channel();
        $channel->exchange_declare(
            self::AMQP_EXCHANGE,
            'topic',
            false,
            true,
            false
        );
        $channel->close();
        $connection->close();
    }

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
    public function testHandleHTTPTasks()
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

    public function testAMQPPublish()
    {
        $promise = new Promise('test');
        $options = [
            'host' => '127.0.0.1',
            'port' => '5672',
            'user' => 'guest',
            'password' => 'guest',
            'vhost' => '/',
            'exchange' => self::AMQP_EXCHANGE,
        ];
        $promise->AMQPPublish('test', 'user.123.create', $options)
            ->withDeadline(time()+3600)
            ->withMaxRetries(5)
            ->withRetryInterval(3);

        $promise->doNow();
    }

    /**
     * @depends testAMQPPublish
     */
    public function testHandleAMQPTasks()
    {
        $this->assertTrue(PageFactory::i()->taskManager->handleTasks());
    }
}