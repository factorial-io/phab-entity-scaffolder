<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Scaffolder\Transformers\Utils\ConfigAccumulator;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;
use Phabalicious\Utilities\Utilities;
use Phabalicious\Scaffolder\Transformers\Utils\EntityForm;

abstract class Base
{

    protected $template = [];

    protected $placeholderService;

    protected $configService;

    protected $data = [];

    public function __construct(ConfigAccumulator $config_service, PlaceholderService $placeholder_service, $data)
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

    protected function getTemplateOverrideData()
    {
      // @TODO Fill $data with existing template data.
      $data = $this->data;
      $out = [];
      $manddatory_keys_map = [
        'id' => 'id',
        'label' => 'label',
      ];
      foreach($manddatory_keys_map as $key => $target) {
        $out[$key] = $data[$target];
      }
      $optional_keys_map = [
        'description' => 'description',
      ];
      foreach($optional_keys_map as $key => $target) {
        if (isset($data[$target])) {
          $out[$key] = $data[$target];
        }
      }
      $out['uuid'] = PlaceholderService::PRESERVE_IF_AVAILABLE;

      // @TODO Find a way to preserve the type of the data
      // after merge.
      // For example boolean false becomes empty during export.
      return $out;
    }
}
