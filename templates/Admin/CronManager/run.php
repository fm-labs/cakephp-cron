<?php
$this->loadHelper('Sugar.Box');

$configName = $this->get('configName');
$config = $this->get('config', []);
/** @var \Cron\Cron\CronTaskResult $result */
$result = $this->get('result');

$this->assign('title', $configName);

$this->Breadcrumbs->add(__d('cron', 'Cron Jobs'), ['action' => 'index']);
$this->Breadcrumbs->add($configName);

$this->Toolbar->addLink(__d('cron', 'Run now'), ['action' => 'run', $configName], ['data-icon' => 'play'])
?>
<div class="view">
    <?= h((string)$result) ?>

    <h3>Cron Task configuration</h3>
    <?= $this->Box->start("Configuration"); ?>
    <?= '' // $this->element('Admin.array_to_list', ['array' => $config]); ?>
    <?= '' // $this->element('Admin.array_to_table', ['array' => [$config]]); ?>
    <?= $this->element('Admin.array_to_tablelist', ['array' => $config]); ?>
    <?= $this->Box->end(); ?>

    <?php if ($result): ?>
        <h3>Logs</h3>
        <?= $this->element('Admin.loglines', ['log' => $result->getLog()]); ?>
    <?php endif; ?>
</div>
