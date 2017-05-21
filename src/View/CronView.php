<?php

namespace Cron\View;


use Cake\View\View;

class CronView extends View
{
    public function render($view = null, $layout = null)
    {
        $this->response->type('text');

        $content = "";

        // @TODO Hide results in production mode
        if (isset($this->viewVars['results'])) {
            foreach ($this->viewVars['results'] as $result) {
                $content .= (string) $result . "\n";
            }
        }

        //$this->response->body($content);
        return $content;
    }
}