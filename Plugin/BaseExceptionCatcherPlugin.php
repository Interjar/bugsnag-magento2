<?php
/**
 * @author Josh Carter <josh@interjar.com>
 */

namespace Interjar\BugSnag\Plugin;

use Bugsnag\Client;
use Interjar\BugSnag\Helper\Config;

/**
 * Base class for any ExceptionCatcher plugin.
 *
 * @package Interjar\BugSnag\Plugin
 */
abstract class BaseExceptionCatcherPlugin
{

    /**
     * Bugsnag Config Helper, used to create Bugsnag\Configuration Instance
     *
     * @var Config
     */
    protected $config;

    /**
     * ExceptionCatcher constructor
     *
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * Handle an exception and attempt to send it to Bugsnag.
     *
     * @param \Exception $exception
     */
    protected function handleException(
        \Exception $exception
    ) {
        if ($this->config->getConfiguration()) {
            $client = new Client($this->config->getConfiguration(), null, Client::makeGuzzle());
            $client->notifyException($exception);
        }
    }

}
