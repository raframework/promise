<?php
/**
 * User: coderd
 * Date: 2017/5/27
 * Time: 16:50
 */

namespace Promise\Config;


use Rapkg\Config\ConfigInterface;

class Database implements ConfigInterface
{
    public function get()
    {
        return [
            'dsn' => 'sqlite:promise.db',
            'username' => null,
            'password' => null,
            'options' => [],
        ];
    }
}