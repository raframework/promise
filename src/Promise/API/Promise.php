<?php
/**
 * User: coderd
 * Date: 2017/6/21
 * Time: 16:27
 */

namespace Promise\API;


use Promise\Lib\Wire\AMQPTask;
use Promise\Lib\Wire\HTTPTask;

class Promise extends APIBase
{
    private $appKey;

    /**
     * @var array
     */
    private $HTTPTaskList = [];

    /**
     * @var array
     */
    private $AMQPTaskList = [];

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

    /**
     * @param $msg
     * @param $routingKey
     * @param array $options In the following format:
     *                      [
     *                          'host' => '127.0.0.1',
     *                          'port' => '5672',
     *                          'user' => 'guest',
     *                          'password' => 'guest',
     *                          'vhost' => '/',
     *                          'exchange' => 'test'
     *                      ]
     * @return AMQPTask
     */
    public function AMQPPublish($msg, $routingKey, array $options = [])
    {
        $AMQPTask = new AMQPTask($options);
        $AMQPTask->publish($msg, $routingKey);
        $this->AMQPTaskList[] = $AMQPTask;

        return $AMQPTask;
    }

    public function doNow()
    {
        if (!$this->addHTTPTasks()) {
            return false;
        }
        if (!$this->addAMQPTasks()) {
            return false;
        }

        return true;
    }

    private function addHTTPTasks()
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

    private function addAMQPTasks()
    {
        if (empty($this->AMQPTaskList)) {
            return true;
        }

        foreach ($this->AMQPTaskList as $AMQPTask) {
            $AMQPTask->validate();
        }

        foreach ($this->AMQPTaskList as $AMQPTask) {
            $this->pf->taskManager->addAMQPTask($this->appKey, $AMQPTask);
        }

        return true;
    }
}