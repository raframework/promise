<?php
/**
 * User: coderd
 * Date: 2017/6/21
 * Time: 16:27
 */

namespace Promise\API;


use Promise\Lib\Wire\HTTPTask;

class Promise extends APIBase
{
    private $appKey;

    /**
     * @var array
     */
    private $HTTPTaskList = [];

    public function __construct($appKey)
    {
        parent::__construct();

        $this->appKey = $appKey;
    }

    /**
     * Send a HTTP request
     *
     * @param $method
     * @param $uri
     * @param array $options
     * @return HTTPTask
     */
    public function sendHTTPRequest($method, $uri, array $options = [])
    {
        $HTTPTask = new HTTPTask();
        $HTTPTask->withRequest($method, $uri, $options);
        $this->HTTPTaskList[] = $HTTPTask;

        return $HTTPTask;
    }

    public function doNow()
    {
        if (empty($this->HTTPTaskList)) {
            return true;
        }

        // Validate
        foreach ($this->HTTPTaskList as $HTTPRequest) {
            $HTTPRequest->validate();
        }

        foreach ($this->HTTPTaskList as $HTTPRequest) {
            $this->pf->taskManager->addHTTPTask($this->appKey, $HTTPRequest);
        }

        return true;
    }
}