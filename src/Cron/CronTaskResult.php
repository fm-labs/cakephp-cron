<?php

namespace Cron\Cron;

/**
 * Class CronTaskResult
 *
 * @package Cron\Cron
 */
class CronTaskResult
{

    /**
     * @var array
     */
    protected $_data = [];

    /**
     * @var array List of log messages
     */
    protected $_log = [];

    /**
     * Constructor
     *
     * Example:
     * new CronTaskResult( 0, 'Custom message', 123456789)
     *
     * new CronTaskResult( [0, 'Custom message', 123456789])
     *
     * new CronTaskResult( true)
     *
     * new CronTaskResult( [true])
     *
     * new CronTaskResult( [false, 'Custom error message'])
     *
     *
     *
     * @param bool|int $status Boolean TRUE maps to STATUS_OK, FALSE to STATUS_FAIL
     * @param string $message Custom result message string
     * @param null $timestamp
     */
    public function __construct($status, $message = "", $timestamp = null, $log = [])
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

        if (!$timestamp) {
            $timestamp = time();
        } elseif ($timestamp instanceof \DateTime) {
            $timestamp = $timestamp->getTimestamp();
        }

        $this->_data = [
            'status'    => (int) $status,
            'message'   => (string) $message,
            'timestamp' => (int) $timestamp
        ];
        $this->_log = $log;
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

    public function getLog()
    {
        return $this->_log;
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
     * Format: [TIMESTAMP] [STATUS] [MESSAGE]
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf("%d %d %s",
            $this->_data['timestamp'],
            $this->_data['status'],
            $this->_data['message']
        );
    }
}