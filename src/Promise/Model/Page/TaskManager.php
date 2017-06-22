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
        $payload = json_encode($HTTPRequest->toArray());

        return $this->rf->task->create($appKey, Constant::TASK_TYPE_HTTP, $payload);
    }
}