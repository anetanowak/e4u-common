<?php
namespace E4u\Common;

use Zend\Config\Config;

class Configuration extends Config
{
    const DEFAULT_ENVIRONMENT = 'development';
    const DEFAULT_ENVIRONMENT_PATH = 'application/config/environment/%s.php';
    const DEFAULT_SESSION_NAME = 'E4uSession';

    public function getSessionName()
    {
        return $this->get('session_name', self::DEFAULT_SESSION_NAME);
    }

    public function getBaseUrl()
    {
        return $this->get('base_url', '/');
    }

    public function mergeEnvironment($environment)
    {
        $path = $this->get('environment_path', self::DEFAULT_ENVIRONMENT_PATH);
        $filename = sprintf($path, $environment);
        $config = is_file($filename)
            ? include($filename) : [];

        $this->merge(new Config($config));
        return $this;
    }
}