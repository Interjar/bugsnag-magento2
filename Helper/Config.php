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
use Magento\Framework\Filesystem\DirectoryList;

class Config
{
    /**
     * Deployment Config Reader
     *
     * @var Reader
     */
    protected $deploymentConfig;

    /**
     * Magento's directory list used for fetching the root folder
     *
     * @var DirectoryList
     */
    protected $directoryList;

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
     * @param DirectoryList $directoryList
     *
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\RuntimeException
     */
    public function __construct(
        Reader $deploymentConfig,
        DirectoryList $directoryList
    )
    {
        $this->deploymentConfig = $deploymentConfig;
        $this->directoryList = $directoryList;
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
        if(isset($this->bugsnagConfig) && is_array($this->bugsnagConfig)) {
            $apiKey = $this->getApiKey();
            $releaseStage = $this->getReleaseStage();
            if ($apiKey) {
                $this->config = new Configuration($apiKey);
                if ($releaseStage) {
                    $this->config->setReleaseStage($releaseStage);
                }

                $projectRoot = $this->getProjectRoot();
                if ($projectRoot) {
                    $this->config->setProjectRoot($projectRoot);
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

    /**
     * Get the project_root full path from env.php if existent.
     *
     * This can also be the boolval `true` if it should be resolved using Magento.
     *
     * @return bool|mixed|string
     */
    public function getProjectRoot() {
        if (array_key_exists('project_root', $this->bugsnagConfig)) {
            $projectRoot = $this->bugsnagConfig['project_root'];
            if ($projectRoot === true) {
                // The root should be resolved by Magento
                return $this->directoryList->getRoot();
            } else {
                return $projectRoot;
            }
        }
        return false;
    }

}
