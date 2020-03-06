<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Utilities\Utilities;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;
use \Symfony\Component\Yaml\Yaml;

class ConfigAccumulator {

    protected $config;

    public function __construct()
    {
        $this->config = [];
    }

    public function get() {
        $config = $this->getRaw();
        $output = [];
        foreach($config as $key => $data) {
            if (!empty($data['dependencies'])) {
                foreach($data['dependencies'] as $category => &$dependencies) {
                    if (!empty($dependencies)) {
                        $dependencies = array_values($dependencies);
                    }
                }
            }
            $output[$key . '.yml'] = $data;
        }
        return $output;
    }

    public function getRaw() {
        return $this->config;
    }

    public function setConfig($config_name, $value) {
        $this->config[$config_name] = $value;
    }

    public function getConfig($config_name) {
        return $this->config[$config_name];
    }

    public function getConfigUUID($config_name) {
        // @TODO : In case uuid is not set, we have to
        // either, throw an error, or respond by generating one.
        return $this->getConfigProperty($config_name, 'uuid');
    }

    public function getConfigProperty($config_name, $property) {
        return $this->config[$config_name][$property];
    }

    public function addDependency($config_name, $category, $dependency) {
        $this->config[$config_name]['dependencies'][$category][] = $dependency;
    }

}
