<?php
/**
 * User: coderd
 * Date: 2017/5/27
 * Time: 11:19
 */

namespace Promise\API;


class HTTP extends APIBase
{
    public function request($method, $uri, array $options = [])
    {
    }

    public function expectStatusCode($statusCode)
    {
        return $this;
    }

    public function expectJsonBody()
    {
        return $this;
    }

    public function expectBodyContainingKey()
    {
        return $this;
    }
}