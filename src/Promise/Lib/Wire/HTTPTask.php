<?php
/**
 * User: coderd
 * Date: 2017/6/21
 * Time: 16:08
 */

namespace Promise\Lib\Wire;


use Psr\Http\Message\ResponseInterface;
use Promise\Lib\Exception\RuntimeException;
use Promise\Lib\Exception\InvalidArgumentException;

class HTTPTask extends TaskBase
{
    const EXPECTED_BODY                   = 'body';
    const EXPECTED_STATUS_CODE            = 'status_code';
    const EXPECTED_BODY_JSON_HAS_KEY      = 'body_json_has_key';
    const EXPECTED_BODY_JSON_FIELD_EQUALS = 'body_json_field_equals';
    const EXPECTED_BODY_CONTAINS_STRING   = 'body_contains_string';

    /**
     * Representation of a request
     *
     * @var array
     */
    protected $request;

    /**
     * Representation of a expected response
     *
     * @var array
     */
    protected $expectedResponse;

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
        $this->expectedResponse[self::EXPECTED_BODY] = (string)$body;

        return $this;
    }

    public function expectStatusCode($statusCode)
    {
        $this->expectedResponse[self::EXPECTED_STATUS_CODE] = (int)$statusCode;

        return $this;
    }

    public function expectBodyJsonHasKey($key)
    {
        $this->expectedResponse[self::EXPECTED_BODY_JSON_HAS_KEY] = $key;

        return $this;
    }

    public function expectBodyJsonFieldEquals($key, $expectedValue)
    {
        $this->expectedResponse[self::EXPECTED_BODY_JSON_FIELD_EQUALS] = [
            'key' => $key,
            'expected_value' => $expectedValue,
        ];

        return $this;
    }

    public function expectBodyContainsString($string)
    {
        $this->expectedResponse[self::EXPECTED_BODY_CONTAINS_STRING] = $string;

        return $this;
    }

    public function getExpectedResponse()
    {
        return $this->expectedResponse;
    }

    public function validate()
    {
        if (empty($this->request)) {
            throw new InvalidArgumentException('Request info must be set');
        }
        if (empty($this->request['method']) || empty($this->request['uri'])) {
            throw new InvalidArgumentException('Request method and uri must be set properly, call withRequest().');
        }
        if (empty($this->getExpectedResponse())) {
            throw new InvalidArgumentException('"Expected response" must be set, call expect***().');
        }

        $this->validateRetryOptions();
    }

    public static function assertResponse(array $expectedResponse, ResponseInterface $response)
    {
        if (empty($expectedResponse)) {
            return true;
        }

        $HTTPTaskAssert = new HTTPTaskAssert($response);
        foreach ($expectedResponse as $key => $value) {
            $assertMethod = 'assert' . str_replace('_', '', ucwords($key, '_'));
            if (!method_exists($HTTPTaskAssert, $assertMethod)) {
                throw new RuntimeException(
                    'Method [' . get_class($HTTPTaskAssert) .  ' ' . $assertMethod . '] not exists');
            }
            if (false === $HTTPTaskAssert->$assertMethod($value)) {
                return false;
            }
        }

        return true;
    }
}