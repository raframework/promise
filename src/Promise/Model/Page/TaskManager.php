<?php
/**
 * User: coderd
 * Date: 2017/6/21
 * Time: 17:52
 */

namespace Promise\Model\Page;


use GuzzleHttp\Client;
use Promise\Config\Constant;
use Promise\Lib\Wire\HTTPTask;

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
        $tasks = $this->rf->task->listBeingHandledTasks();
        var_dump($tasks);
        if (empty($tasks)) {
            return;
        }

        foreach ($tasks as $task) {
            if ((int)$task['type'] === Constant::TASK_TYPE_HTTP) {
                $this->handleHTTPTask($task);
            }
        }
    }

    public function handleHTTPTask($task)
    {
        $payload = json_decode($task['payload'], true);
        if (empty($payload)) {
            return false;
        }
        var_dump($payload);

        $request = $payload['request'];
        $client = new Client();
        $response = $client->request($request['method'], $request['uri'], $request['options']);
        $statusCode = $response->getStatusCode();
        var_dump($statusCode);

        return true;
    }
}