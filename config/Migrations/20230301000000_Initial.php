<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class Initial extends AbstractMigration
{
    public $autoId = false;

    /**
     * Up Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-up-method
     * @return void
     */
    public function up(): void
    {
        $this->table('cron_jobresults')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('cron_job_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('status', 'tinyinteger', [
                'comment' => 'Result status',
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('message', 'string', [
                'comment' => 'Result message',
                'default' => '0',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('log', 'text', [
                'comment' => 'log output',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('timestamp', 'integer', [
                'comment' => 'execution timestamp',
                'default' => null,
                'limit' => null,
                'null' => true,
                'signed' => false,
            ])
            ->addColumn('client_ip', 'string', [
                'comment' => 'client IP address',
                'default' => null,
                'limit' => 46,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $this->table('cron_jobs')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('name', 'string', [
                'comment' => 'Unique task alias',
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('class', 'string', [
                'comment' => 'CronTask URL in CakePHP requestAction Format',
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('desc', 'string', [
                'comment' => 'Name of CronTask',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('interval', 'integer', [
                'comment' => 'Interval of execution in seconds',
                'default' => '0',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('is_active', 'boolean', [
                'comment' => 'Active Flag',
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('last_status', 'tinyinteger', [
                'comment' => 'Last execution status',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('last_message', 'string', [
                'comment' => 'Last execution message',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('last_executed', 'integer', [
                'comment' => 'Last execution date',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'name',
                ],
                ['unique' => true]
            )
            ->create();
    }

    /**
     * Down Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-down-method
     * @return void
     */
    public function down(): void
    {
        $this->table('cron_jobresults')->drop()->save();
        $this->table('cron_jobs')->drop()->save();
    }
}
