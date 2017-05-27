<?php
/**
 * User: coderd
 * Date: 2017/5/27
 * Time: 16:47
 */

namespace Promise\Model\Data\Table;


class Message extends PromiseBase
{
    const COL_ID = 'id';
    const COL_VERSION = 'version';
    const COL_APP_KEY = 'app_key';
    const COL_PAYLOAD = 'payload';
    const COL_STATUS = 'status';
    const COL_UPDATED_AT = 'updated_at';
    const COL_CREATED_AT = 'created_at';

    public function __construct()
    {
        parent::__construct('message');
    }

    private static $columns = [
        self::COL_ID,
        self::COL_VERSION,
        self::COL_APP_KEY,
        self::COL_PAYLOAD,
        self::COL_STATUS,
        self::COL_UPDATED_AT,
        self::COL_CREATED_AT,
    ];

    public function create($version, $appKey, $payload)
    {
        $now = time();
        $values = [
            self::COL_VERSION => $version,
            self::COL_APP_KEY => $appKey,
            self::COL_PAYLOAD => $payload,
            self::COL_UPDATED_AT => $now,
            self::COL_CREATED_AT => $now,
        ];

        return $this->insert($values);
    }

    public function listBy(array $wheres = [])
    {
        return $this->select(self::$columns, $wheres);
    }
}