<?php
/**
 * Plugin to catch any uncaught exceptions in Cron jobs.
 *
 * @author Josh Carter <josh@interjar.com>
 */

namespace Interjar\BugSnag\Plugin;

use Magento\Framework\App\Bootstrap;
use Magento\Framework\App\Cron;

class CronExceptionCatcher extends BaseExceptionCatcherPlugin
{

    /**
     * Catch any exceptions and notify an instance of \Bugsnag\Client
     *
     * @param Cron $subject
     * @param Bootstrap $bootstrap
     * @param \Exception $exception
     *
     * @return array
     */
    public function beforeCatchException(
        Cron $subject,
        Bootstrap $bootstrap,
        \Exception $exception
    ) {
        $this->handleException($exception);

        return [$bootstrap, $exception];
    }

}
