<?php
/**
 * User: coderd
 * Date: 2017/5/27
 * Time: 17:02
 */

namespace Promise\Model\Page\Row;


use Promise\Model\Page\PageBase;

class Message extends PageBase
{
    const VERSION = 1;

    public function create($appKey, $payload)
    {
        return $this->tf->message->create(self::VERSION, $appKey, $payload);
    }

    public function listBy(array $wheres = [])
    {
        return $this->tf->message->listBy($wheres);
    }
}