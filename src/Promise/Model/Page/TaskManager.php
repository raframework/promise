<?php
/**
 * User: coderd
 * Date: 2017/6/21
 * Time: 17:52
 */

namespace Promise\Model\Page;


use GuzzleHttp\Client;
use Promise\Config\Constant;
use Promise\Lib\Log;
use Promise\Lib\Wire\HTTPTask;
use GuzzleHttp\Exception\ClientException;

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

    public function handleTasks()
    {
        $tasks = $this->rf->task->listPendingTasks();
        if (empty($tasks)) {
            return;
        }

        Log::debug('pending tasks: ' . json_encode($tasks));

        foreach ($tasks as $task) {
            if ((int)$task['type'] === Constant::TASK_TYPE_HTTP) {
                $this->handleHTTPTask($task);
            }
        }
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
}