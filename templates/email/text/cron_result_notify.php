<?php
$status = $this->get('status');
$message = $this->get('message');
$timestamp = $this->get('timestamp');
$log = $this->get('log', []);
?>
================================
Cronjob Result Notification
================================


STATUS: <?= $status."\n" ?>

MESSAGE: <?= $message."\n"; ?>

TIMESTAMP: <?= sprintf("Date: %s\n", date("Y-m-d H:i:s", $timestamp));?> (<?= $timestamp; ?>)

LOG:
------------------
<?php $i = 0; ?>
<?php foreach((array) $log as $line): ?>
	<?php echo ++$i . ': ' . $line."\n"; ?>
<?php endforeach; ?>
------------------