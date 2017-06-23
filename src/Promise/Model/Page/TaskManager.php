<?php
/**
 * User: coderd
 * Date: 2017/6/21
 * Time: 17:52
 */

namespace Promise\Model\Page;


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
}