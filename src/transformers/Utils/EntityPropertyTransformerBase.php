<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Utilities\Utilities;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;

abstract class EntityPropertyTransformerBase {
    protected $template = [];

    protected $placeholderService;

    public function __construct()
    {
        $this->template = \Symfony\Component\Yaml\Yaml::parseFile($this->getTemplateFile());
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
    public function getConfigName($id = '')
    {
        return $this->getName() . '.' . $id;
    }

    protected function getTemplateOverrideData($data = []) 
    {
        return [
            'uuid' => PlaceholderService::PRESERVE_IF_AVAILABLE,
        ];
    }
}