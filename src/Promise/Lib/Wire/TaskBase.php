<?php
/**
 * User: coderd
 * Date: 2017/6/23
 * Time: 16:06
 */

namespace Promise\Lib\Wire;


use Promise\Lib\Exception\InvalidArgumentException;

class TaskBase
{
    protected $deadline;

    protected $maxRetries;

    protected $retryInterval;

    /**
     * @param int $deadline  unix timestamp
     * @return $this
     * @throws InvalidArgumentException
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

    /**
     * @param $maxRetries
     * @throws InvalidArgumentException
     * @return $this
     */
    public function withMaxRetries($maxRetries)
    {
        if (!is_int($maxRetries) || $maxRetries <= 0) {
            throw new InvalidArgumentException(
                'The max_retries must be an integer with a positive value'
            );
        }

        $this->maxRetries = $maxRetries;

        return $this;
    }

    public function getMaxRetries()
    {
        return $this->maxRetries;
    }

    /**
     * @param $retryInterval
     * @throws InvalidArgumentException
     * @return $this
     */
    public function withRetryInterval($retryInterval)
    {
        if (!is_int($retryInterval) || $retryInterval <= 0) {
            throw new InvalidArgumentException(
                'The retry_interval must be an integer with a positive value'
            );
        }

        $this->retryInterval = $retryInterval;

        return $this;
    }

    public function getRetryInterval()
    {
        return $this->retryInterval;
    }
}