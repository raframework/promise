<?php
/**
 * User: coderd
 * Date: 2017/5/27
 * Time: 11:21
 */

namespace Promise\Command;


use Promise\Lib\Log;

class Daemon extends CommandBase
{
    public function help()
    {
        return $this->synopsis();
    }

    public function run(array $args)
    {
        Log::debug('daemon is running');

        $this->pf->taskManager->handleTasks();

        return 0;
    }

    public function synopsis()
    {
        return 'Run promise as daemon';
    }
}