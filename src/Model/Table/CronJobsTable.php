<?php
namespace Cron\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CronJobs Model
 *
 * @property \Cake\ORM\Association\HasMany $CronJobresults
 *
 * @method \Cron\Model\Entity\CronJob get($primaryKey, $options = [])
 * @method \Cron\Model\Entity\CronJob newEntity($data = null, array $options = [])
 * @method \Cron\Model\Entity\CronJob[] newEntities(array $data, array $options = [])
 * @method \Cron\Model\Entity\CronJob|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Cron\Model\Entity\CronJob patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Cron\Model\Entity\CronJob[] patchEntities($entities, array $data, array $options = [])
 * @method \Cron\Model\Entity\CronJob findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CronJobsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('cron_jobs');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('CronJobresults', [
            'foreignKey' => 'cron_job_id',
            'className' => 'Cron.CronJobresults',
            'order' => ['CronJobresults.id' => 'ASC'],
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->requirePresence('name', 'create')
            ->notEmptyString('name')
            ->add('name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->requirePresence('class', 'create')
            ->notEmptyString('class');

        $validator
            ->allowEmptyString('desc');

        $validator
            ->integer('interval')
            ->requirePresence('interval', 'create')
            ->notEmptyString('interval');

        $validator
            ->boolean('is_active')
            ->requirePresence('is_active', 'create')
            ->notEmptyString('is_active');

        $validator
            ->integer('last_status')
            ->allowEmptyString('last_status');

        $validator
            ->allowEmptyString('last_message');

        $validator
            ->integer('last_executed')
            ->allowEmptyString('last_executed');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['name']));

        return $rules;
    }
}
