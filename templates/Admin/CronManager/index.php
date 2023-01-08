<?php

$this->assign('title', 'Cron Jobs');

$this->Breadcrumbs->add(__('Cron'), ['action' => 'index']);
$this->Breadcrumbs->add(__('Cron Jobs'));


$cronConfigs = $this->get('cronConfigs', []);

$formatter = function ($url) {
    $parts = [];
    krsort($url);
    foreach ($url as $key => $val) {
        if (is_array($val)) {
            $val = json_encode($val);
        }
        $parts[] = sprintf("<strong>%s:</strong> %s", $key, $val);
    }
    return join("<br>", $parts);
};
?>
<div class="index">
    <table class="table table-sm">
        <tr>
            <th><?= __d('cron', 'Cron Task'); ?></th>
            <th><?= __d('cron', 'Configuration'); ?></th>
            <th class="actions"><?= __d('admin', 'Actions'); ?></th>
        </tr>
        <tbody>
        <?php foreach ($cronConfigs as $alias => $cronConfig) : ?>
            <tr>
                <td><?=$this->Html->link(
                        $alias,
                        ['action' => 'view', $alias],
                        []); ?></td>
                <td><?= $formatter($cronConfig); ?></td>
                <td class="actions">
                    <?= $this->Html->link(
                        __d('cron', 'Run now'),
                        ['action' => 'run', $alias],
                        ['class' => 'btn btn-xs btn-outline-primary', 'data-icon' => 'play']); ?>
                    <?= $this->Html->link(
                        __d('cron', 'Disable'),
                        ['action' => 'disable', $alias],
                        ['class' => 'btn btn-xs btn-outline-primary', 'data-icon' => 'times']); ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>