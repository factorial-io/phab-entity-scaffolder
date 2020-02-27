<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

require_once __DIR__ . '/EntityFormTransformer.php';
require_once __DIR__ . '/FieldFieldTransformer.php';
require_once __DIR__ . '/FieldStorageTransformer.php';

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Utilities\Utilities;
use Phabalicious\Scaffolder\Transformers\Utils\ConfigService;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;
use Phabalicious\Scaffolder\Transformers\Utils\ESBase;
use Phabalicious\Scaffolder\Transformers\Utils\EntityFormTransformer;
use Phabalicious\Scaffolder\Transformers\Utils\FieldFieldTransformer;
use Phabalicious\Scaffolder\Transformers\Utils\FieldStorageTransformer;

abstract class EntityBase extends ESBase
{
    const ENTITY_TYPE = '???';
    const MODULE = '???';
    protected $bundle;

    public function __construct(ConfigService $config_service, PlaceholderService $placeholder_service, $data)
    {
        parent::__construct($config_service, $placeholder_service, $data);
        $this->bundle = $this->data['id'];
        $config = Utilities::mergeData($this->template, $this->getTemplateOverrideData());
        $this->configService->setConfig($this->getConfigName(), $config);
        $configs = $this->transformFields();
        foreach($configs as $key => $value) {
            $this->configService->setConfig($key, $value);
        }
    }

    public function getConfigName() 
    {
        return $this::ENTITY_TYPE . '.type.' . $this->bundle;
    }

    protected function transformFields() {
        $data = $this->data;
        $field_configs = [];
        if (!empty($data['fields'])) {
            $weight = 0;
            $entityFormTransformer = new EntityFormTransformer($this::ENTITY_TYPE, $data, 'default');
            foreach ($data['fields'] as $key => $field) {
                $field['id'] = $key;
                $weight++;
                if (empty($field['weight'])) {
                    $field['weight'] = $weight;
                }
                $fieldStorageTransformer = new FieldStorageTransformer($this::ENTITY_TYPE, $field, $data);
                if (!empty($this->getDependencies())) {
                    foreach($this->getDependencies() as $category => $dependencies) {
                        if ($dependencies) {
                            foreach($dependencies as $dependency) {
                                $fieldStorageTransformer->setDependency($category, $dependency);
                            }
                        }
                    }
                }
                $this->configService->setConfig($fieldStorageTransformer->getConfigName(), $fieldStorageTransformer->getConfig());
                


                $fieldFieldTransformer = new FieldFieldTransformer($this::ENTITY_TYPE, $field, $data);                
                $this->configService->setConfig($fieldFieldTransformer->getConfigName(), $fieldFieldTransformer->getConfig());

                $entityFormTransformer->setDependency('config', $fieldFieldTransformer->getConfigName($data['id']));
            }
            $entityFormTransformer->setDependency('config', $this->getConfigName($data['id']));
            $this->configService->setConfig($entityFormTransformer->getConfigName(), $entityFormTransformer->getConfig());
        }
        return $field_configs;
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
        return $out;
    }
}