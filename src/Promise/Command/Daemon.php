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
        return $this->synopsis();
    }

    public function run(array $args)
    {
        $listResult = $this->rf->task->listBy();
        var_dump($listResult);

        return 0;
    }

    public function synopsis()
    {
        return 'Run promise as daemon';
    }
}