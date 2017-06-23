<?php
/**
 * User: coderd
 * Date: 2017/5/27
 * Time: 16:47
 */

namespace Promise\Model\Data\Table;


class Task extends PromiseBase
{
    const COL_ID = 'id';
    const COL_VERSION = 'version';
    const COL_APP_KEY = 'app_key';
    const COL_TYPE = 'type';
    const COL_PAYLOAD = 'payload';
    const COL_STATUS = 'status';
    const COL_DEADLINE = 'deadline';
    const COL_MAX_RETRIES = 'max_retries';
    const COL_RETRIES = 'retries';
    const COL_RETRY_INTERVAL = 'retry_interval';
    const COL_LAST_RETRIED_AT = 'last_retried_at';
    const COL_UPDATED_AT = 'updated_at';
    const COL_CREATED_AT = 'created_at';

    public function __construct()
    {
        parent::__construct('task');
    }

    private static $columns = [
        self::COL_ID,
        self::COL_VERSION,
        self::COL_APP_KEY,
        self::COL_TYPE,
        self::COL_PAYLOAD,
        self::COL_STATUS,
        self::COL_DEADLINE,
        self::COL_MAX_RETRIES,
        self::COL_RETRIES,
        self::COL_RETRY_INTERVAL,
        self::COL_LAST_RETRIED_AT,
        self::COL_UPDATED_AT,
        self::COL_CREATED_AT,
    ];

    public function create($version, $appKey, array $extraParams = [])
    {
        $now = time();
        $values = [
            self::COL_VERSION => $version,
            self::COL_APP_KEY => $appKey,
            self::COL_UPDATED_AT => $now,
            self::COL_CREATED_AT => $now,
        ];
        if ($extraParams) {
            $values = array_merge($values, $extraParams);
        }

        return $this->insert($values);
    }

    public function listBy(array $wheres = [])
    {
        return $this->select(self::$columns, $wheres);
    }
}