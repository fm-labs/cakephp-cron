<?php
namespace Cron\Model\Entity;

use Cake\ORM\Entity;

/**
 * CronJobresult Entity
 *
 * @property int $id
 * @property int $cron_job_id
 * @property int $status
 * @property string $message
 * @property string $log
 * @property int $timestamp
 * @property string $client_ip
 * @property \Cake\I18n\Time $created
 *
 * @property \Cron\Model\Entity\CronJob $cron_job
 */
class CronJobresult extends Entity
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
}
