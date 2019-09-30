<?php
/**
 * Plugin to catch any uncaught exceptions in the HTTP layer,
 *
 * @author Josh Carter <josh@interjar.com>
 */

namespace Interjar\BugSnag\Plugin;

use Magento\Framework\App\Bootstrap;
use Magento\Framework\App\Http;

class HttpExceptionCatcher extends BaseExceptionCatcherPlugin
{

    /**
     * Catch any exceptions and notify an instance of \Bugsnag\Client
     *
     * @param Http $subject
     * @param Bootstrap $bootstrap
     * @param \Exception $exception
     *
     * @return array
     */
    public function beforeCatchException(
        Http $subject,
        Bootstrap $bootstrap,
        \Exception $exception
    ) {
        $this->handleException($exception);

        return [$bootstrap, $exception];
    }
}
