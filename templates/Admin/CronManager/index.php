<?php
$this->assign('title', __d('cron', 'Cron Tasks'));

$this->Breadcrumbs->add(__d('cron', 'Cron'), ['action' => 'index']);
$this->Breadcrumbs->add(__d('cron', 'Cron Jobs'));

$cronTasks = $this->get('cronTasks', []);
?>
<div class="index">
    <table class="table table-sm">
        <tr>
            <th><?= __d('cron', 'Task Name'); ?></th>
            <th><?= __d('cron', 'Classname'); ?></th>
            <th><?= __d('cron', 'Interval'); ?></th>
            <th><?= __d('cron', 'Enabled'); ?></th>
            <th><?= __d('cron', 'Last message'); ?></th>
            <th><?= __d('cron', 'Last executed'); ?></th>
            <th class="actions"><?= __d('cron', 'Actions'); ?></th>
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
                <?php if ($cronTask['_last']): ?>
                    <td>
                            <?= h($cronTask['_last']['message']) ?>
                    </td>
                    <td>
                        <?= $this->Time->timeAgoInWords($cronTask['_last']['timestamp']) ?>
                    </td>
                <?php else: ?>
                    <td colspan="2">
                        <?= __d('cron', 'Not executed') ?>
                    </td>
                <?php endif; ?>
                <td class="actions">
                    <?= $this->Html->link(
                        __d('cron', 'Run'),
                        ['action' => 'run', $alias],
                        ['class' => 'btn btn-xs btn-outline-primary', 'data-icon' => 'play']); ?>
                    <?= $this->Html->link(
                        __d('cron', 'Force Run'),
                        ['action' => 'run', $alias, '?' => ['force' => true]],
                        ['class' => 'btn btn-xs btn-outline-primary', 'data-icon' => 'play']); ?>
                    <?= $this->Html->link(
                        __d('cron', 'Simulate'),
                        ['prefix' => false, 'plugin' => 'Cron', 'controller' => 'Cron', 'action' => 'run', $alias],
                        ['class' => 'btn btn-xs btn-outline-primary', 'data-icon' => 'play', 'target' => '_blank']); ?>
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