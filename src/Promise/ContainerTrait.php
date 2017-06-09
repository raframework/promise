<?php
/**
 * User: coderd
 * Date: 2017/6/9
 * Time: 9:43
 */

namespace Promise;


use Promise\Model\Page\Row\RowFactory;
use Promise\Model\Data\Table\TableFactory;

trait ContainerTrait
{
    /**
     * @var TableFactory
     */
    protected $tf;

    /**
     * @var RowFactory
     */
    protected $rf;

    public function __construct()
    {
        if ($this->tf === null) {
            $this->tf = TableFactory::i();
        }

        if ($this->rf === null) {
            $this->rf = RowFactory::i();
        }
    }
}