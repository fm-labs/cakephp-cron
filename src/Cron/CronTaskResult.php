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

    private array $_log;

    /**
     * Constructor
     *
     * @param bool $success
     * @param string|null $message Custom result message string
     */
    public function __construct(bool $success, ?string $message, array $log = [])
    {
        $this->_status = (int)$success;
        $this->_message = $message;
        $this->_timestamp = time();
        $this->_log = $log;
    }

    /**
     * @param string|null $message
     * @return $this
     */
    public function setFailed(?string $message = null): static
    {
        $this->_status = self::STATUS_FAIL;
        $this->_message = $message;
        $this->appendLog(sprintf("Status changed: STATUS:%s MSG:%s", $this->_status, $this->_message));

        return $this;
    }

    /**
     * @param string|null $message
     * @return $this
     */
    public function setSuccess(?string $message = null): static
    {
        $this->_status = self::STATUS_OK;
        $this->_message = $message;
        $this->appendLog(sprintf("Status changed: STATUS:%s MSG:%s", $this->_status, $this->_message));

        return $this;
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
     * @param array $log
     * @return CronTaskResult
     */
    public function setLog(array $log): static
    {
        $this->_log = $log;

        return $this;
    }

    public function appendLog(string|array $log): static
    {
        if (is_string($log)) {
            $log = [$log];
        }

        foreach ($log as $line) {
            $this->_log[] = $line;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getLog(): array
    {
        return $this->_log;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'status' => $this->_status,
            'message' => $this->_message,
            'timestamp' => $this->_timestamp,
            'log' => $this->_log,
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
