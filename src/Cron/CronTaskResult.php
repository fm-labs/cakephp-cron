<?php
declare(strict_types=1);

namespace Cron\Cron;

/**
 * Class CronTaskResult
 *
 * @package Cron\Cron
 */
class CronTaskResult
{
    public const STATUS_FAIL = 0;
    public const STATUS_OK = 1;

    protected int $_status = -1;

    protected string $_message;

    protected int $_timestamp;

    /**
     * Constructor
     *
     * @param bool $success
     * @param string|null $message Custom result message string
     */
    public function __construct(bool $success, ?string $message)
    {
        $this->_status = (int)$success;
        $this->_message = $message;
        $this->_timestamp = time();
    }

    public function setFailed(?string $message = null)
    {
        $this->_status = self::STATUS_FAIL;
        $this->_message = $message;
    }

    public function setSuccess(?string $message = null)
    {
        $this->_status = self::STATUS_OK;
        $this->_message = $message;
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
