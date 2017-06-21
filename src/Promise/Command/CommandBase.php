<?php
/**
 * User: coderd
 * Date: 2017/6/9
 * Time: 9:00
 */

namespace Promise\Command;


use Promise\ContainerTrait;
use Rapkg\Cli\CommandInterface;

abstract class CommandBase implements CommandInterface
{
    use ContainerTrait;
}