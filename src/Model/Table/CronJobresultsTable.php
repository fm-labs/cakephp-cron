<?php
declare(strict_types=1);

namespace Cron\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CronJobresults Model
 *
 * @property \Cake\ORM\Association\BelongsTo $CronJobs
 *
 * @method \Cron\Model\Entity\CronJobresult get($primaryKey, $options = [])
 * @method \Cron\Model\Entity\CronJobresult newEntity($data = null, array $options = [])
 * @method \Cron\Model\Entity\CronJobresult[] newEntities(array $data, array $options = [])
 * @method \Cron\Model\Entity\CronJobresult|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Cron\Model\Entity\CronJobresult patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Cron\Model\Entity\CronJobresult[] patchEntities($entities, array $data, array $options = [])
 * @method \Cron\Model\Entity\CronJobresult findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CronJobresultsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('cron_jobresults');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('CronJobs', [
            'foreignKey' => 'cron_job_id',
            'joinType' => 'INNER',
            'className' => 'Cron.CronJobs',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->integer('status')
            ->requirePresence('status', 'create')
            ->notEmptyString('status');

        $validator
            ->requirePresence('message', 'create')
            ->notEmptyString('message');

        $validator
            ->allowEmptyString('log');

        $validator
            ->integer('timestamp')
            ->allowEmptyString('timestamp');

        $validator
            ->allowEmptyString('client_ip');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['cron_job_id'], 'CronJobs'));

        return $rules;
    }
}
