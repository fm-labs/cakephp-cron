<?php
namespace Cron\Mailer\Preview;

use Cron\Cron\CronTaskResult;
use DebugKit\Mailer\MailPreview;

class CronMailPreview extends MailPreview
{
    /**
     * @return \Cron\Mailer\CronMailer
     */
    public function cronResultNotify(): \Cron\Mailer\CronMailer
    {
        $result = new CronTaskResult("OK", "Test cron task passed");
        /** @var \Cron\Mailer\CronMailer $mailer */
        $mailer = $this->getMailer("Cron.Cron");
        return $mailer
            ->cronResultNotify("cron_mail_preview", $result);
    }
}