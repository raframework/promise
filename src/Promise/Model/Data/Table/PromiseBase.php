<?php
/**
 * User: coderd
 * Date: 2017/5/27
 * Time: 16:47
 */

namespace Promise\Model\Data\Table;


use Rapkg\Model\Table;
use Promise\Config\Database;

class PromiseBase extends Table
{
    protected function dbConfig()
    {
        return new Database();
    }
}