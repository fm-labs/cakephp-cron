<?php

namespace Cron\View;

use Cake\View\View;

/**
 * Class CronView
 *
 * @package Cron\View
 */
class CronView extends View
{
    public function render($view = null, $layout = null)
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
