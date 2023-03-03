<?php
$this->assign('title', __d('cron', 'Cron Tasks'));

$this->Breadcrumbs->add(__d('cron', 'Cron'), ['action' => 'index']);
$this->Breadcrumbs->add(__d('cron', 'Cron Jobs'));

$cronTasks = $this->get('cronTasks', []);
debug($cronTasks);
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
        <?php foreach ($cronTasks as $alias => $cronTask) : ?>
            <?php $cronTask['enabled'] = $cronTask['enabled'] ?? false ?>
            <tr>
                <td><?=$this->Html->link(
                        $alias,
                        ['action' => 'view', $alias],
                        []); ?>
                </td>
                <td><?= $cronTask['className'] ?? "-" ?></td>
                <td><?= $cronTask['interval'] ?? "-" ?></td>
                <td><?= $cronTask['enabled'] ? "Yes" : "No" ?></td>
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