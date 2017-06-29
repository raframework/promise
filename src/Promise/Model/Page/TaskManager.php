<?php
/**
 * User: coderd
 * Date: 2017/6/21
 * Time: 17:52
 */

namespace Promise\Model\Page;


use Promise\Lib\Log;
use GuzzleHttp\Client;
use Promise\Config\Constant;
use Promise\Lib\Support\Str;
use Promise\Lib\Wire\AMQPTask;
use Promise\Lib\Wire\HTTPTask;
use PhpAmqpLib\Message\AMQPMessage;
use GuzzleHttp\Exception\ClientException;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class TaskManager extends PageBase
{
    public function addHTTPTask($appKey, HTTPTask $HTTPTask)
    {
        $deadline = $HTTPTask->getDeadline();
        $payload = json_encode([
            'request' => $HTTPTask->getRequest(),
            'expected_response' => $HTTPTask->getExpectedResponse(),
        ]);
        $extraParams = [
            'payload' => $payload,
            'deadline' => $deadline,
            'max_retries' => $HTTPTask->getMaxRetries(),
            'retry_interval' => $HTTPTask->getRetryInterval(),
        ];

        return $this->rf->task->create($appKey, Constant::TASK_TYPE_HTTP, $extraParams);
    }

    public function addAMQPTask($appKey, AMQPTask $AMQPTask)
    {
        $payload = json_encode($AMQPTask->toPayload());
        $extraParams = [
            'payload' => $payload,
            'deadline' => $AMQPTask->getDeadline(),
            'max_retries' => $AMQPTask->getMaxRetries(),
            'retry_interval' => $AMQPTask->getRetryInterval(),
        ];

        return $this->rf->task->create($appKey, Constant::TASK_TYPE_AMQP, $extraParams);
    }

    public function handleTasks()
    {
        $tasks = $this->rf->task->listPendingTasks();
        if (empty($tasks)) {
            return true;
        }

        Log::debug('pending tasks: ' . json_encode($tasks));

        foreach ($tasks as $task) {
            if ((int)$task['type'] === Constant::TASK_TYPE_HTTP
                && false === $this->handleHTTPTask($task)) {
                return false;
            }
            if ((int)$task['type'] === Constant::TASK_TYPE_AMQP
                && false === $this->handleAMQPTask($task)) {
                return false;
            }
        }

        return true;
    }

    public function handleHTTPTask(array $task)
    {
        Log::debug('handling HTTP task: ' . json_encode($task));

        $payload = json_decode($task['payload'], true);
        if (empty($payload)) {
            return false;
        }

        $request = $payload['request'];
        $client = new Client([
            'timeout' => 3
        ]);
        try {
            $response = $client->request($request['method'], $request['uri'], $request['options']);
        } catch (ClientException $e) {
            $response = $e->getResponse();
        }

        $assertResult = HTTPTask::assertResponse($payload['expected_response'], $response);
        Log::debug('assert-response result: ' . json_encode($assertResult));

        if ($assertResult) {
            $result = $this->rf->task->retrySucceeded($task['id']);
        } else {
            $result = $this->rf->task->retryFailed($task['id']);
        }
        Log::debug('update status result: ' . json_encode($result));

        if ($result === false) {
            return false;
        }

        return true;
    }

    public function handleAMQPTask(array $task)
    {
        Log::debug('handling AMQP task: ' . json_encode($task));

        $payload = json_decode($task['payload'], true);
        if (empty($payload)) {
            return false;
        }

        try {
            $options = $payload['options'];
            $connection = new AMQPStreamConnection(
                $options['host'],
                $options['port'],
                $options['user'],
                $options['password'],
                $options['vhost']
            );
            $channel = $connection->channel();
            $channel->basic_publish(
                new AMQPMessage($payload['msg']),
                $options['exchange'],
                $payload['routing_key']
            );
            $channel->close();

            $result = $this->rf->task->retrySucceeded($task['id']);
        } catch (\Exception $e) {
            Log::error(
                'AMQP: publish message failed with exception: '
                . Str::exceptionToStringWithoutLF($e)
            );
            $result = $this->rf->task->retryFailed($task['id']);
        }

        Log::info('AMQP: publish message successfully');

        try {
            $connection->close();
        } catch (\Exception $e) {}

        Log::debug('update status result: ' . json_encode($result));

        if ($result === false) {
            return false;
        }

        return true;
    }
}