<?php
/**
 * User: coderd
 * Date: 2017/5/27
 * Time: 16:57
 */

namespace Promise\Model\Data\Table;


use Rapkg\Design\AbstractPropertyFactory;

/**
 * Class TableFactory
 * @package Promise\Model\Data\Table
 * @property Queue $queue
 */
class TableFactory extends AbstractPropertyFactory
{
    private function __construct(){}

    protected function namespacePrefix()
    {
        return __NAMESPACE__ . '\\';
    }

    private static $instance;

    /**
     * @return TableFactory
     */
    public static function i()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}