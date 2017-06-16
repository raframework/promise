<?php
/**
 * User: coderd
 * Date: 2017/6/12
 * Time: 9:41
 */

namespace Promise\Model\Page;


class HTTP
{
    /**
     * @var array
     */
    private $data = [
        'request'=> [],
        'expected_response' => []
    ];

    public function request($method, $uri, array $options = [])
    {
        $this->data['request'] = [
            'method' => $method,
            'uri' => $uri,
            'options' => $options,
        ];

        return $this;
    }

    public function expectBody($body)
    {
        $this->data['expected_response']['body'] = (string)$body;

        return $this;
    }

    public function expectStatusCode($statusCode)
    {
        $this->data['expected_response']['status_code'] = (int)$statusCode;

        return $this;
    }

    public function expectBodyJsonHasKey($key)
    {
        $this->data['expected_response']['body_json_has_key'] = $key;

        return $this;
    }

    public function expectBodyJsonFieldEquals($key, $value)
    {
        $this->data['expected_response']['body_json_field_equals'] = [
            'key' => $key,
            'value' => $value,
        ];

        return $this;
    }

    public function expectBodyContainsString($string)
    {
        $this->data['expected_response']['body_Contains_string'] = $string;

        return $this;
    }
}