<?php
/**
 * Helper for BugSnag
 *
 * @author Josh Carter <josh@interjar.com>
 */
namespace Interjar\BugSnag\Helper;

use Bugsnag\Configuration;
use Magento\Framework\App\DeploymentConfig\Reader;
use Magento\Framework\Config\File\ConfigFilePool;

class Config
{
    /**
     * Deployment Config Reader
     *
     * @var Reader
     */
    protected $deploymentConfig;

    /**
     * Full array of data from env.php
     *
     * @var array
     */
    protected $env;

    /**
     * Array of data from env.php associated with Bugsnag
     *
     * @var array
     */
    protected $bugsnagConfig;

    /**
     * Bugsnag Configuration Object Instance
     *
     * @var Configuration
     */
    protected $config;

    /**
     * Config constructor
     *
     * @param Reader $deploymentConfig
     */
    public function __construct(
        Reader $deploymentConfig
    )
    {
        $this->deploymentConfig = $deploymentConfig;
        $this->env = $deploymentConfig->load(ConfigFilePool::APP_ENV);
        if(isset($this->env['bugsnag'])) {
            $this->bugsnagConfig = $this->env['bugsnag'];
        }
    }

    /**
     * Return \Bugsnag\Configuration Instance
     *
     * @return bool|Configuration
     */
    public function getConfiguration()
    {
        if ($this->config instanceof Configuration) {
            return $this->config;
        } else if(isset($this->bugsnagConfig) && is_array($this->bugsnagConfig)) {
            $apiKey = $this->getApiKey();
            if ($apiKey) {
                $this->config = new Configuration($apiKey);
                $releaseStage = $this->getReleaseStage();
                if ($releaseStage) {
                    $this->config->setReleaseStage($releaseStage);
                }
                return $this->config;
            }
        }
        return false;
    }

    /**
     * Return api_key value from env.php if existent
     *
     * @return bool|mixed
     */
    public function getApiKey()
    {
        if (array_key_exists('api_key', $this->bugsnagConfig)) {
            return $this->bugsnagConfig['api_key'];
        }
        return false;
    }

    /**
     * Return release_stage value from env.php if existent
     *
     * @return bool|mixed
     */
    public function getReleaseStage()
    {
        if (array_key_exists('release_stage', $this->bugsnagConfig)) {
            return $this->bugsnagConfig['release_stage'];
        }
        return false;
    }

}