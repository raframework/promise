<?php
/**
 * User: coderd
 * Date: 2017/6/21
 * Time: 16:27
 */

namespace Promise\API;


use Promise\Model\Page;
use Promise\Lib\Wire\HTTP;

class Promise extends APIBase
{
    private $appKey;

    private $HTTPRequestPool = [];

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
     * @return HTTP
     */
    public function sendHTTPRequest($method, $uri, array $options = [])
    {
        $HTTP = new HTTP();
        $HTTP->withRequest($method, $uri, $options);
        $this->HTTPRequestPool[] = $HTTP;

        return $HTTP;
    }

    public function doNow()
    {
        if (empty($this->HTTPRequestPool)) {
            return true;
        }

        foreach ($this->HTTPRequestPool as $HTTPRequest) {
            $this->pf->taskManager->addHTTPRequest($this->appKey, $HTTPRequest);
        }

        return true;
    }
}