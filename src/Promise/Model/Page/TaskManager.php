<?php
/**
 * User: coderd
 * Date: 2017/6/21
 * Time: 17:52
 */

namespace Promise\Model\Page;


use Promise\Lib\Wire\HTTP;
use Promise\Config\Constant;

class TaskManager extends PageBase
{
    public function addHTTPRequest($appKey, HTTP $HTTPRequest)
    {
        $deadline = $HTTPRequest->getDeadline();
        $payload = json_encode($HTTPRequest->toArray());
        $extraParams = [
            'deadline' => $deadline,
            'payload' => $payload,
        ];

        return $this->rf->task->create($appKey, Constant::TASK_TYPE_HTTP, $extraParams);
    }
}