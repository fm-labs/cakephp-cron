<?php
namespace Cron\Model\Entity;

use Cake\ORM\Entity;

/**
 * CronJob Entity
 *
 * @property int $id
 * @property string $name
 * @property string $class
 * @property string $desc
 * @property int $interval
 * @property bool $is_active
 * @property int $last_status
 * @property string $last_message
 * @property int $last_executed
 * @property \Cake\I18n\Time $modified
 *
 * @property \Cron\Model\Entity\CronJobresult[] $cron_jobresults
 */
class CronJob extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];

    protected $_virtual = [
        'offset' => true
    ];

    protected function _getOffset()
    {
        if ($this->last_executed < 1) {
            return -1;
        }

        return $this->last_executed + $this->interval - time();
    }
}
