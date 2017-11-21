<?php $this->loadHelper('Backend.DataTable'); ?>

<div class="index">

    <?php echo $this->DataTable->create([
        'filter' => false,
        'fields' => [
            'id',
            'className',
            'interval'
        ],
        'rowActions' => [
            'run' => [__('Run'), ['action' => 'run', ':id']],
            'stats' => [__('Stats'), ['action' => 'stats', ':id']],
        ]
    ], $tasks)->render(); ?>

    <?php debug($tasks); ?>
</div>