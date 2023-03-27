<?php
declare(strict_types=1);

namespace Cron\View;

use Cake\Core\Configure;
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
        $isDebug = /*Configure::read('debug') || */ $this->getRequest()->getQuery('debug');

        $this->response = $this->response->withType('text');

        $content = "";

        if ($isDebug) {
            if (isset($this->viewVars['results'])) {
                foreach ($this->viewVars['results'] as $taskName => $result) {
                    $content .= sprintf("[%s] %s\n", $taskName, (string)$result);
                }
            }
        } else {
            $content = "OK";
        }

        //$this->response->body($content);
        return $content;
    }
}
