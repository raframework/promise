<?php
/**
 * User: coderd
 * Date: 2017/6/21
 * Time: 16:08
 */

namespace Promise\Lib\Wire;


use Promise\Lib\Exception\InvalidArgumentException;

class HTTP
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

    protected $retryOptions = [];

    protected $deadline = 0;

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

    /**
     * @param array $options defines the retry options
     *                       It must be an associative in the format:
     *                       [
     *                          'retries' => int    retry times
     *                          'interval' => float retry interval, second
     *                          'till_time' => int  retry till time, unix timestamp
     *                       ]
     * @return $this
     */
    public function withRetryOptions(array $options)
    {
        self::checkRetryOptions($options);
        $this->retryOptions = $options;

        return $this;
    }

    private static function checkRetryOptions(array $options)
    {
        if (!isset($options['retries']) || !is_int($options['retries']) || $options['retries'] <= 0) {
            throw new InvalidArgumentException(
                'The options.retries must be an integer with a positive value'
            );
        }
        if (!isset($options['interval']) || !is_float($options['interval']) || $options['interval'] <= 0.0) {
            throw new InvalidArgumentException(
                'The options.interval must be a float with a positive value'
            );
        }
    }

    /**
     * @param int $deadline  unix timestamp
     * @throws InvalidArgumentException
     * @return $this
     */
    public function withDeadline($deadline)
    {
        $now = time();
        if (!is_int($deadline) || $deadline < $now) {
            throw new InvalidArgumentException(
                'The "deadline" must be an integer with '
                . 'a positive value larger than current timestamp "' . $now . '"'
            );
        }

        $this->deadline = $deadline;

        return $this;
    }

    public function getDeadline()
    {
        return $this->deadline;
    }

    public function toArray()
    {
        return [
            'request' => $this->request,
            'expected_response' => $this->expectedResponse,
            'retry_options' => $this->retryOptions,
        ];
    }
}