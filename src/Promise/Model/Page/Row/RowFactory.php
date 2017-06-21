<?php
/**
 * User: coderd
 * Date: 2017/6/9
 * Time: 8:57
 */

namespace Promise\Model\Page\Row;


use Rapkg\Design\AbstractPropertyFactory;

/**
 * Class RowFactory
 * @package Promise\Model\Page\Row
 * @property Task $task
 */
class RowFactory extends AbstractPropertyFactory
{
    private function __construct(){}

    /**
     * @return string
     */
    protected function namespacePrefix()
    {
        return __NAMESPACE__ . '\\';
    }

    private static $instance;

    /**
     * @return RowFactory
     */
    public static function i()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}