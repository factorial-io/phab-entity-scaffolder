<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Scaffolder\Transformers\Utils\ConfigService;
use Phabalicious\Utilities\Utilities;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;
use Phabalicious\Scaffolder\Transformers\Utils\EntityFormTransformer;

abstract class EsBase
{

    protected $template = [];

    protected $placeholderService;

    protected $configService;

    protected $data = [];

    public function __construct(ConfigService $config_service, PlaceholderService $placeholder_service, $data)
    {
        $this->template = \Symfony\Component\Yaml\Yaml::parseFile($this->getTemplateFile());
        $this->configService = $config_service;
        $this->placeholderService = $placeholder_service;
        $this->data = $data;
    }

    /**
     * {@inheritDoc}
     */
    protected function getTemplateFile()
    {
        return $this->getTemplateDir() . '/' . $this->getTemplateFileName();
    }

    /**
     * {@inheritDoc}
     */
    protected function getTemplateDir()
    {
        return __DIR__ . '/templates';
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigurations()
    {
        return $this->configService->get();
    }

    public function getDependencies()
    {
        return [];
    }

}
