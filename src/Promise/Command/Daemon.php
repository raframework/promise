<?php
/**
 * User: coderd
 * Date: 2017/5/27
 * Time: 11:21
 */

namespace Promise\Command;


class Daemon extends CommandBase
{
    public function help()
    {
        return 'Run promise in daemon';
    }

    public function run(array $args)
    {
//        $result = $this->rf->task->schema();
//        var_dump($result);
        $listResult = $this->rf->task->listBy();
        var_dump($listResult);

        return 0;
    }

    public function synopsis()
    {
        return 'Run promise in daemon';
    }
}