<?php
/**
 * User: coderd
 * Date: 2017/6/21
 * Time: 18:00
 */

namespace Promise\Config;


class Constant
{
    const TASK_TYPE_HTTP = 1;
    const TASK_TYPE_AMQP = 2;

    const TASK_STATUS_NEWLY_ADDED = 0;
    const TASK_STATUS_RUNNING = 1;
    const TASK_STATUS_FAILED = 2;
    const TASK_STATUS_SUCCESS = 3;
}