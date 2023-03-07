<?php
$this->loadHelper('Sugar.Box');

$taskName = $this->get('taskName');
$config = $this->get('config', []);
$log = $this->get('log', []);
/** @var \Cron\Cron\CronTaskResult $result */
$result = $this->get('result');

$this->assign('title', $taskName);

$this->Breadcrumbs->add(__d('cron', 'Cron Jobs'), ['action' => 'index']);
$this->Breadcrumbs->add($taskName);

$this->Toolbar->addLink(__d('cron', 'Run now'), ['action' => 'run', $taskName], ['data-icon' => 'play'])
?>
<div class="view">
    <h3>Result</h3>
    <pre class="p-3"><?= h((string)$result) ?></pre>

    <h3>Configuration</h3>
    <?= $this->Box->start("Configuration"); ?>
    <?= $this->element('Admin.array_to_tablelist', ['array' => $config]); ?>
    <?= $this->Box->end(); ?>

    <h3>Log</h3>
    <?= $this->element('Admin.loglines', ['log' => $log]); ?>
</div>
