<?php $this->loadHelper('Backend.DataTable'); ?>
<?php $this->loadHelper('Time'); ?>
<div class="view">
    <h2>Cron Stats</h2>

    <?php if (isset($stats)): ?>
        <?php $this->DataTable->create([
            'model' => false,
            'data' => $stats,
            'fields' => [
                'timestamp' => ['formatter' => function($val) { return $this->Time->timeAgoInWords($val); }]
            ]
        ]);
        echo $this->DataTable->render();
        ?>
        <?php debug($stats->toArray()); ?>
    <?php endif; ?>
</div>