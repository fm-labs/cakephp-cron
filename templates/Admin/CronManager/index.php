<?php
$this->assign('title', 'Cron Jobs');

$this->Breadcrumbs->add(__('Cron'), ['action' => 'index']);
$this->Breadcrumbs->add(__('Cron Jobs'));

$cronConfigs = $this->get('cronConfigs', []);
?>
<div class="index">
    <table class="table table-sm">
        <tr>
            <th><?= __d('cron', 'Task Name'); ?></th>
            <th><?= __d('cron', 'Classname'); ?></th>
            <th><?= __d('cron', 'Interval'); ?></th>
            <th><?= __d('cron', 'Enabled'); ?></th>
            <th><?= __d('cron', 'Last executed'); ?></th>
            <th class="actions"><?= __d('admin', 'Actions'); ?></th>
        </tr>
        <tbody>
        <?php foreach ($cronConfigs as $alias => $cronConfig) : ?>
            <?php $cronConfig['enabled'] = $cronConfig['enabled'] ?? false ?>
            <tr>
                <td><?=$this->Html->link(
                        $alias,
                        ['action' => 'view', $alias],
                        []); ?>
                </td>
                <td><?= $cronConfig['className'] ?? "-" ?></td>
                <td><?= $cronConfig['interval'] ?? "-" ?></td>
                <td><?= $cronConfig['enabled'] ? "Yes" : "No" ?></td>
                <td>?</td>
                <td class="actions">
                    <?= $this->Html->link(
                        __d('cron', 'Run now'),
                        ['action' => 'run', $alias],
                        ['class' => 'btn btn-xs btn-outline-primary', 'data-icon' => 'play']); ?>
                    <?php /* echo $this->Html->link(
                        __d('cron', 'Disable'),
                        ['action' => 'disable', $alias],
                        ['class' => 'btn btn-xs btn-outline-primary', 'data-icon' => 'times']); */ ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>