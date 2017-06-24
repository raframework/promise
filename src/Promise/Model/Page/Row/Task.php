<?php
/**
 * User: coderd
 * Date: 2017/5/27
 * Time: 17:02
 */

namespace Promise\Model\Page\Row;


use Promise\Model\Page\PageBase;

class Task extends PageBase
{
    const VERSION = 1;

    public function create($appKey, $type, array $extraParams = [])
    {
        $extraParams = array_merge(['type' => $type], $extraParams);

        return $this->tf->task->create(self::VERSION, $appKey, $extraParams);
    }

    public function listBy(array $wheres = [])
    {
        return $this->tf->task->listBy($wheres);
    }

    public function listBeingHandledTasks()
    {
        return $this->tf->task->listBeingHandledTasks();
    }
}