<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

abstract class EntityPropertyBase
{
    protected $template = [];

    protected $placeholderService;
    protected $config;

    public function __construct()
    {
        $this->template = PlaceholderService::parseTemplateFile($this->getTemplateFile());
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
        return $this->getConfigName() . '.yml';
    }

    /**
     * Get the Drupal config name.
     */
    public function getConfigName()
    {
        return '???';
    }

    protected function getTemplateOverrideData()
    {
        return [
            'uuid' => PlaceholderService::REUSE_OR_CREATE_VALUE,
        ];
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function setConfig($config)
    {
        $this->config = $config;
        return $this;
    }

    public function setDependency($category, $config_name)
    {
        $this->config['dependencies'][$category][] = $config_name;
        return $this;
    }
}
