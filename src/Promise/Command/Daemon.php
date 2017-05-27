<?php
/**
 * User: coderd
 * Date: 2017/5/27
 * Time: 11:21
 */

namespace Promise\Command;


use Promise\Model\Page\Row\Message;
use Rapkg\Cli\CommandInterface;

class Daemon implements CommandInterface
{
    public function help()
    {
        return 'Run promise in daemon';
    }

    public function run(array $args)
    {
        echo 'Daemon promise is running...' . "\n";

        $appKey = 'test';
        $payload = 'lala';
        $queue = new Message();
        $result = $queue->create($appKey, $payload);
        $listResult = $queue->listBy();
        var_dump($result, $listResult);

        return 0;
    }

    public function synopsis()
    {
        return 'Run promise in daemon';
    }
}