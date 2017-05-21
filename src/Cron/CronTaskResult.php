<?php

namespace Cron\Cron;

/**
 * Class CronTaskResult
 *
 * @package Cron\Cron
 */
class CronTaskResult
{
    const STATUS_NORUN = 0;
    const STATUS_FAIL = 1;
    const STATUS_OK = 2;

    /**
     * @var array
     */
    protected $_data = [];

    /**
     * Constructor
     *
     * Example:
     * new CronTaskResult('task_name', 0, 'Custom message', 123456789)
     *
     * new CronTaskResult('task_name', [0, 'Custom message', 123456789])
     *
     * new CronTaskResult('task_name', true)
     *
     * new CronTaskResult('task_name', [true])
     *
     * new CronTaskResult('task_name', [false, 'Custom error message'])
     *
     *
     *
     * @param $taskName
     * @param bool|int $status Boolean TRUE maps to STATUS_OK, FALSE to STATUS_FAIL
     * @param string $message Custom result message string
     * @param null $timestamp
     */
    public function __construct($taskName, $status, $message = "", $timestamp = null)
    {
        if (is_array($status)) {
            if (count($status) == 1) {
                list($status) = $status;
            } elseif (count($status) == 2) {
                list($status, $message) = $status;
            } elseif (count($status) >= 3) {
                list($status, $message, $timestamp) = $status;
            }
        }

        if (is_bool($status)) {
            $status = ($status === true) ? static::STATUS_OK : static::STATUS_FAIL;
        }

        if ($timestamp instanceof \DateTime) {
            $timestamp = $timestamp->getTimestamp();
        } elseif (!$timestamp) {
            $timestamp = time();
        }

        $this->_data = [
            'taskName'  => (string) $taskName,
            'status'    => (int) $status,
            'message'   => (string) $message,
            'timestamp' => (int) $timestamp
        ];
    }

    /**
     * @return string
     */
    public function getTaskName()
    {
        return $this->_data['taskName'];
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->_data['status'];
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->_data['message'];
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->_data['timestamp'];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->_data;
    }

    /**
     * Returns formatted string
     * Format: [TASKNAME] [TIMESTAMP] [STATUS] [MESSAGE]
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf("%s %d %d %s",
            $this->_data['taskName'],
            $this->_data['timestamp'],
            $this->_data['status'],
            $this->_data['message']
        );
    }
}