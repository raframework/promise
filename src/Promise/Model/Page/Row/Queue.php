<?php
/**
 * User: coderd
 * Date: 2017/5/27
 * Time: 17:02
 */

namespace Promise\Model\Page\Row;


use Promise\Model\Page\PageBase;

class Queue extends PageBase
{
    public function create($appKey, $payload)
    {
        return $this->tf->queue->create($appKey, $payload);
    }

    public function listBy(array $wheres = [])
    {
        return $this->tf->queue->listBy($wheres);
    }
}