<?php
/**
 * User: coderd
 * Date: 2017/6/27
 * Time: 15:18
 */

namespace Promise\Lib\Wire;


use Psr\Http\Message\ResponseInterface;

class HTTPTaskAssert
{
    /**
     * @var ResponseInterface
     */
    private $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function assertBody($expected)
    {
        return (string)$this->response->getBody() === $expected;
    }

    public function assertStatusCode($expected)
    {
        return $this->response->getStatusCode() === $expected;
    }

    public function assertBodyJsonHasKey($expected)
    {
        $body = json_decode($this->response->getBody(), true);
        if (empty($body)) {
            return false;
        }

        $key = $expected;
        $keySegments = explode('.', $key);
        $keySegmentCount = count($keySegments);
        $i = 0;
        foreach ($keySegments as $keySegment) {
            $i++;
            if (!isset($body[$keySegment])) {
                return false;
            }
            // If it is the deepest key
            if ($i === $keySegmentCount) {
                return true;
            }

            $body = $body[$keySegment];
        }

        return false;
    }

    /**
     * @param array $expected In this format:
     *                          [
     *                              'key' => key.key2.key3,      // string
     *                              'expected_value' => value // mixed
     *                          ]
     * @return bool
     */
    public function assertBodyJsonFieldEquals($expected)
    {
        $body = json_decode($this->response->getBody(), true);
        if (empty($body)) {
            return false;
        }

        $key = $expected['key'];
        $expectedValue = $expected['expected_value'];
        $keySegments = explode('.', $key);
        $keySegmentCount = count($keySegments);
        $i = 0;
        foreach ($keySegments as $keySegment) {
            $i++;
            if (!isset($body[$keySegment])) {
                return false;
            }
            // If it is the deepest key
            if ($i === $keySegmentCount) {
                if ($body[$keySegment] === $expectedValue) {
                    return true;
                }
                return false;
            }

            $body = $body[$keySegment];
        }

        return false;
    }


    public function assertBodyContainsString($expected)
    {
        return false !== strpos((string)$this->response->getBody(), $expected);
    }
}