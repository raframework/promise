<?php
/**
 * User: coderd
 * Date: 2017/6/21
 * Time: 18:05
 */

namespace Promise\Model\Page;


use Rapkg\Design\AbstractPropertyFactory;

/**
 * Class PageFactory
 * @package Promise\Model\Page
 * @property TaskManager $taskManager
 */
class PageFactory extends AbstractPropertyFactory
{

    /**
     * @return string
     */
    protected function namespacePrefix()
    {
        return __NAMESPACE__ . '\\';
    }

    private static $instance;

    /**
     * @return PageFactory
     */
    public static function i()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}