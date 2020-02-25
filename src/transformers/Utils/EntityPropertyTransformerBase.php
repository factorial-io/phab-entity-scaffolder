<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Utilities\Utilities;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;
use \Symfony\Component\Yaml\Yaml;

abstract class EntityPropertyTransformerBase {
    protected $template = [];

    protected $placeholderService;
    protected $result;

    public function __construct()
    {
        $this->template = Yaml::parseFile($this->getTemplateFile());
        $this->placeholderService = new PlaceholderService();
    }

    protected function getTemplateFile() 
    {
        return $this->getTemplateDir() . '/' . $this->getTemplateFileName();
    }

    protected function getTemplateDir() 
    {
        return __DIR__ . '/templates';
    }

    protected function getTemplateFileName() 
    {
        return $this->getConfigName('template') . '.yml';
    }

    /**
     * Get the Drupal config name.
     */
    public function getConfigName()
    {
        return '???';
    }

    protected function getTemplateOverrideData($data = []) 
    {
        return [
            'uuid' => PlaceholderService::PRESERVE_IF_AVAILABLE,
        ];
    }

    public function getOutput() {
        return [$this->getConfigName() . '.yml' => $this->result];
    }

    public function setDependency($category, $config_name) {
        $this->result['dependencies'][$category][] = $config_name;
    }

}