<?php
/**
 * User: coderd
 * Date: 2017/5/27
 * Time: 17:00
 */

namespace Promise\Model\Page;


use Promise\Model\Data\Table\TableFactory;

class PageBase
{
    /**
     * @var TableFactory
     */
    protected $tf;

    public function __construct()
    {
        if ($this->tf === null) {
            $this->tf = TableFactory::i();
        }
    }
}