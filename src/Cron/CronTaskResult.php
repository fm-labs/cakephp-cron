<?php
declare(strict_types=1);

namespace Cron\Cron;

use Cake\Utility\Hash;

/**
 * Class CronTaskResult
 *
 * Example:
 * new CronTaskResult( 1, 'Custom success message' )
 * new CronTaskResult( [0, 'Custom failure message'] )
 * new CronTaskResult( true )
 * new CronTaskResult( [true] )
 * new CronTaskResult( [false, 'Custom failure message'] )
 *
 * @package Cron\Cron
 */
class CronTaskResult
{
    public const STATUS_NOT_EXECUTED = -1;
    public const STATUS_FAIL = 0;
    public const STATUS_OK = 1;

    protected int $_status = self::STATUS_NOT_EXECUTED;

    protected string $_message = "";

    protected int $_timestamp;

    /**
     * @var array List of log messages
     */
    protected array $_log = [];

    /**
     * @var array Meta data
     */
    protected array $_meta = [];

    /**
     * Constructor
     *
     * @param bool|int $status Boolean TRUE maps to STATUS_OK, FALSE to STATUS_FAIL
     * @param string $message Custom result message string
     * @param array|null $meta Optional Meta data
     * @param array|null $log Optional Log lines
     */
    public function __construct($status, string $message = "", ?array $meta = [], ?array $log = [])
    {
        $this->_status = (int)$status;
        $this->_message = $message;
        $this->_meta = (array)$meta;
        $this->_log = (array)$log;
        $this->_timestamp = time();
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->_status;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->_message;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->_timestamp;
    }

    /**
     * @param array $log
     * @return $this
     */
    public function setLog(array $log): CronTaskResult
    {
        $this->_log = $log;
        return $this;
    }

    /**
     * @return array|mixed
     */
    public function getLog(): array
    {
        return $this->_log;
    }

    /**
     * @param array $meta
     * @return $this
     */
    public function setMetaData(array $meta): CronTaskResult
    {
        $this->_meta = Hash::merge($this->_meta, $meta);
        return $this;
    }

    /**
     * @return array|mixed
     */
    public function getMetaData($key = null): array
    {
        if ($key === null) {
            return $this->_meta;
        }
        return Hash::get($this->_meta, $key);
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->_status === self::STATUS_OK;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'status' => $this->_status,
            'message' => $this->_message,
            'timestamp' => $this->_timestamp
        ];
    }

    /**
     * Returns formatted string
     * Format: [TIMESTAMP] [STATUS] [MESSAGE]
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            "%d %d %s",
            $this->_timestamp,
            $this->_status,
            $this->_message
        );
    }
}
