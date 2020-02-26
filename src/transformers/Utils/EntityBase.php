<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Utilities\Utilities;
use Phabalicious\Scaffolder\Transformers\Utils\ConfigService;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;
use Phabalicious\Scaffolder\Transformers\Utils\ESBase;

abstract class EntityBase extends ESBase
{
    const ENTITY_TYPE = '???';
    protected $bundle;

    public function __construct(ConfigService $config_service, PlaceholderService $placeholder_service, $data)
    {
        parent::__construct($config_service, $placeholder_service, $data);
        $this->bundle = $this->data['id'];
        $config = Utilities::mergeData($this->template, $this->getTemplateOverrideData());
        $this->configService->setConfig($this->getConfigName(), $config);
        // $this->transformFields();
    }

    public static function getName()
    {
        return self::ENTITY_TYPE;
    }

    public function getConfigName() 
    {
        return $this::ENTITY_TYPE . '.type.' . $this->bundle;
    }

    protected function transformFields() {
        // @TODO Port.
        return;
        $data = $this->data;
        $field_configs = [];
        if (!empty($data['fields'])) {
            $weight = 0;
            $entityFormTransformer = new EntityFormTransformer($this::ENTITY_NAME, $data, 'default');
            foreach ($data['fields'] as $key => $field) {
                $field['id'] = $key;
                $weight++;
                if (empty($field['weight'])) {
                    $field['weight'] = $weight;
                }
                $fieldStorageTransformer = new FieldStorageTransformer($this::ENTITY_NAME, $field, $data);
                $field_configs += $fieldStorageTransformer->transformDependend();
                $fieldFieldTransformer = new FieldFieldTransformer($this::ENTITY_NAME, $field, $data);
                $field_configs += $fieldFieldTransformer->transformDependend();

                $entityFormTransformer->attachField($fieldFieldTransformer);
            }
            $entityFormTransformer->setDependency('config', $this->getConfigName($data['id']));
            $field_configs += $entityFormTransformer->getOutput();
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
