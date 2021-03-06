<?php
declare(strict_types=1);

namespace Cron\View;

use Cake\View\View;

/**
 * Class CronView
 *
 * @package Cron\View
 */
class CronView extends View
{
    public function render(?string $template = null, $layout = null): string
    {
        $this->response = $this->response->withType('text');

        $content = "";

        // @TODO Hide results in production mode
        if (isset($this->viewVars['results'])) {
            foreach ($this->viewVars['results'] as $taskName => $result) {
                $content .= sprintf("[%s] %s\n", $taskName, (string)$result);
            }
        }

        //$this->response->body($content);
        return $content;
    }
}
