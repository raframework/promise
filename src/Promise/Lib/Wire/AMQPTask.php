<?php
/**
 * User: coderd
 * Date: 2017/6/29
 * Time: 15:32
 */

namespace Promise\Lib\Wire;


use Promise\Lib\Exception\InvalidArgumentException;

class AMQPTask extends TaskBase
{
    /**
     * @var array
     */
    private $options;

    /**
     * @var string
     */
    private $msg;

    /**
     * @var string
     */
    private $routingKey;

    /**
     * AMQPTask constructor.
     * @param array $options In the following format:
     *                      [
     *                          'host' => '127.0.0.1',
     *                          'port' => '5672',
     *                          'user' => 'guest',
     *                          'password' => 'guest',
     *                          'vhost' => '/',
     *                          'exchange' => 'test'
     *                      ]
     */
    public function __construct(array $options)
    {
        $this->checkOptions($options);
        $this->options = $options;
    }

    private function checkOptions(array $options)
    {
        $requiredKeys = [
            'host',
            'port',
            'user',
            'password',
            'vhost',
            'exchange'
        ];
        foreach ($requiredKeys as $requiredKey) {
            if (empty($options[$requiredKey])) {
                throw new InvalidArgumentException(
                    'AMQPTask: options key "' . $requiredKey . '" is required'
                );
            }
        }
    }

    public function publish($msg, $routingKey)
    {
        $this->msg = $msg;
        $this->routingKey = $routingKey;
    }

    public function toPayload()
    {
        return [
            'msg' => $this->msg,
            'routing_key' => $this->routingKey,
            'options' => $this->options,
        ];
    }

    public function validate()
    {
        $this->validateRetryOptions();
    }
}