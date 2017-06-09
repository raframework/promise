<?php
/**
 * User: coderd
 * Date: 2017/5/27
 * Time: 11:21
 */

namespace Promise\Command;


use Rapkg\Cli\CommandInterface;

class Daemon extends CommandBase implements CommandInterface
{
    public function help()
    {
        return 'Run promise in daemon';
    }

    public function run(array $args)
    {
        $listResult = $this->rf->message->listBy();
        var_dump($listResult);

        return 0;
    }

    public function synopsis()
    {
        return 'Run promise in daemon';
    }
}