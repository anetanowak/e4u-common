<?php
namespace E4u\Common;

use E4u\Common\Exception\ConfigException;
use Zend\Config\Config;

class Configuration
{
    const DEFAULT_ENVIRONMENT = 'development';
    const DEFAULT_ENVIRONMENT_PATH = 'application/config/environment/%s.php';
    const DEFAULT_SESSION_NAME = 'E4uSession';

    /**
     * @var Config
     */
    protected $config;

    /**
     * Configuration constructor.
     * @param string $filename
     * @param string $environment
     */
    public function __construct($filename, $environment = null)
    {
        $this->loadConfig($filename);
        $this->mergeEnvironment($environment ?: $this->detectEnvironment());
        $this->setReadOnly();
    }

    protected function loadConfig($filename)
    {
        $config = $this->loadFile($filename, true)->toArray();
        $this->config = new Config($config, true);
        return $this;
    }

    /**
     * @return $this
     */
    protected function setReadOnly()
    {
        $this->config->setReadOnly();
        return $this;
    }

    /**
     * @return string
     */
    protected function detectEnvironment()
    {
        if ($this->isCommandLineInterface() && isset($_SERVER['argv'])) {
            foreach ($_SERVER['argv'] as $key => $arg) {
                if (preg_match('/^--env(ironment)?=([^\s]+)$/', $arg, $regs)) {
                    unset($_SERVER['argv'][$key]);
                    return $regs[2];
                }

                if ($arg == '--test') {
                    unset($_SERVER['argv'][$key]);
                    return 'test';
                }
            }
        }

        if (is_file('.environment')) {
            return trim(file_get_contents('.environment'));
        }

        return self::DEFAULT_ENVIRONMENT;
    }

    /**
     * @return bool
     */
    protected function isCommandLineInterface()
    {
        return (php_sapi_name() === 'cli');
    }

    /**
     * @param  string $filename
     * @param  bool $throwExceptionIfFileDoesNotExist
     * @return Config
     */
    protected function loadFile($filename, $throwExceptionIfFileDoesNotExist = false)
    {
        if (!is_file($filename)) {
            if ($throwExceptionIfFileDoesNotExist) {
                throw new ConfigException(sprintf('Configuration file: %s not found.', $filename));
            }

            return new Config([]);
        }

        return new Config(include($filename));
    }

    /**
     * @param  string $filename
     * @return $this
     */
    protected function mergeFile($filename)
    {
        $config = $this->loadFile($filename);
        $this->config->merge($config);
        return $this;
    }

    /**
     * @param  string $name
     * @param  mixed $default
     * @return Config|mixed
     */
    public function get($name, $default = null)
    {
        return $this->config->get($name, $default);
    }

    /**
     * @return string
     */
    public function getSessionName()
    {
        return $this->get('session_name', self::DEFAULT_SESSION_NAME);
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->get('base_url', '/');
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->config->get('environment');
    }

    /**
     * @param  string $environment
     * @return $this
     */
    protected function setEnvironment($environment)
    {
        $this->config->environment = $environment;
        return $this;
    }

    /**
     * @param  string $environment
     * @return $this
     */
    protected function mergeEnvironment($environment)
    {
        $path = $this->get('environment_path', self::DEFAULT_ENVIRONMENT_PATH);
        $filename = sprintf($path, $environment);

        $this->mergeFile($filename);
        $this->setEnvironment($environment);
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->config->toArray();
    }
}