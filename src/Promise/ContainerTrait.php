<?php
/**
 * User: coderd
 * Date: 2017/6/9
 * Time: 9:43
 */

namespace Promise;


use Promise\Lib\Log;
use Psr\Log\LogLevel;
use Promise\Model\Page\PageFactory;
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

    /**
     * @var PageFactory
     */
    protected $pf;

    private static $initialized = false;

    public function __construct()
    {
        if ($this->tf === null) {
            $this->tf = TableFactory::i();
        }

        if ($this->rf === null) {
            $this->rf = RowFactory::i();
        }

        if ($this->pf === null) {
            $this->pf = PageFactory::i();
        }

        self::init();
    }

    private static function init()
    {
        if (self::$initialized) {
            return;
        }

        Log::initDefaultLogger('/home/huangshaowen/www/log/promise/promise.log');
        Log::setDefaultLevel(LogLevel::DEBUG);
    }
}