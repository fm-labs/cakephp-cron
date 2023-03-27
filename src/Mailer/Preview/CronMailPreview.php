<?php
namespace Cron\Mailer\Preview;

use Cron\Cron\CronTaskResult;
use Cron\Mailer\CronMailer;
use DebugKit\Mailer\MailPreview;

class CronMailPreview extends MailPreview
{
    /**
     * @return CronMailer
     */
    public function cronResultNotify(): CronMailer
    {
        $result = new CronTaskResult("OK", "Test cron task passed");
        /** @var CronMailer $mailer */
        $mailer = $this->getMailer("Cron.Cron");
        return $mailer
            ->cronResultNotify("cron_mail_preview", $result);
    }
}