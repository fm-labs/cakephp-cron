<?php
declare(strict_types=1);

namespace Cron\Cron;

use Cake\Utility\Hash;

/**
 * Class CronTaskResult
 *
 * Example:
 * new CronTaskResult( 0, 'Custom message', 123456789)
 * new CronTaskResult( [0, 'Custom message', 123456789])
 * new CronTaskResult( true)
 * new CronTaskResult( [true])
 * new CronTaskResult( [false, 'Custom error message'])
 *
 * @package Cron\Cron
 */
class CronTaskResult
{
    protected const STATUS_NOT_EXECUTED = -1;
    protected const STATUS_FAIL = 0;
    protected const STATUS_OK = 1;

    protected $_status = self::STATUS_NOT_EXECUTED;

    protected $_message = "";

    protected $_timestamp;

    /**
     * @var array List of log messages
     */
    protected $_log = [];

    /**
     * @var array Meta data
     */
    protected $_meta = [];

    /**
     * Constructor
     *
     * @param bool|int $status Boolean TRUE maps to STATUS_OK, FALSE to STATUS_FAIL
     * @param string $message Custom result message string
     * @param array $meta Optional Meta data
     * @param array $log Optional Log lines
     */
    public function __construct($status, $message = "", array $meta = [], array $log = [])
    {
        if (is_array($status)) {
            [$status, $message] = $status;
        }
        $this->_status = (int)$status;
        $this->_message = (string)$message;
        $this->_log = $log;
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
    public function setLog(array $log)
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
    public function setMetaData(array $meta)
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
    public function toArray()
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
