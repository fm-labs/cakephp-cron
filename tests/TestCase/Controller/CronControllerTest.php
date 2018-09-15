<?php

namespace Cron\Test\TestCase\Controller;

use Cake\Core\Configure;
use Cake\TestSuite\IntegrationTestCase;

/**
 * Class CronControllerTest
 *
 * @package Cron\Test\TestCase\Controller
 */
class CronControllerTest extends IntegrationTestCase
{
    /**
     * Setup
     */
   public function setUp()
   {
        parent::setUp();
        Configure::write('Cron.Tasks', [
            'test' => [
               'className' => 'Cron\\Test\\TestCase\\TestCronTask',
               'interval' => 0
            ]
        ]);
   }

    /**
     * Test index method
     */
    public function testIndex()
    {
        $this->markTestIncomplete();
        return;

        // No session data set.
        $this->get('/cron/index');

        $this->assertResponseCode(200);
        $this->assertHeaderContains('Content-Type', 'text/plain');
        $this->assertResponseContains("2 TEST OK");
    }

}