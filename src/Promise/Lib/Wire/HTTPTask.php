<?php
/**
 * User: coderd
 * Date: 2017/6/21
 * Time: 16:08
 */

namespace Promise\Lib\Wire;


use Promise\Lib\Exception\InvalidArgumentException;

class HTTPTask extends TaskBase
{
    /**
     * Representation of a request
     *
     * @var array
     */
    protected $request = [];

    /**
     * Representation of a expected response
     *
     * @var array
     */
    protected $expectedResponse = [];

    private static $supportedMethods = [
        'GET',
        'POST',
        'PUT',
        'DELETE',
        'HEAD',
        'PATCH',
    ];

    private static function isSupportedMethod($method)
    {
        return in_array($method, self::$supportedMethods, true);
    }
    
    public function withRequest($method, $uri, array $options = [])
    {
        if (!self::isSupportedMethod($method)) {
            throw new InvalidArgumentException('Unsupported HTTP method "' . $method . '"');
        }

        $this->request = [
            'method' => $method,
            'uri' => $uri,
            'options' => $options,
        ];

        return $this;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function expectBody($body)
    {
        $this->expectedResponse['body'] = (string)$body;

        return $this;
    }

    public function expectStatusCode($statusCode)
    {
        $this->expectedResponse['status_code'] = (int)$statusCode;

        return $this;
    }

    public function expectBodyJsonHasKey($key)
    {
        $this->expectedResponse['body_json_has_key'] = $key;

        return $this;
    }

    public function expectBodyJsonFieldEquals($key, $expectedValue)
    {
        $this->expectedResponse['body_json_field_equals'] = [
            'key' => $key,
            'expected_value' => $expectedValue,
        ];

        return $this;
    }

    public function expectBodyContainsString($string)
    {
        $this->expectedResponse['body_contains_string'] = $string;

        return $this;
    }

    public function getExpectedResponse()
    {
        return $this->expectedResponse;
    }
}