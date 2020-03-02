<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Utilities\Utilities;
use Phabalicious\Scaffolder\Transformers\Utils\ConfigAccumulator;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;
use Phabalicious\Scaffolder\Transformers\Utils\Base;
use Phabalicious\Scaffolder\Transformers\Utils\EntityForm;
use Phabalicious\Scaffolder\Transformers\Utils\FieldField;
use Phabalicious\Scaffolder\Transformers\Utils\FieldStorage;
use Phabalicious\Scaffolder\Transformers\Utils\FieldWidget;

abstract class EntityBase extends Base
{
    protected $bundle;

    public function getEntityType() {
      return '???';
    }

    public function getTemplateFileName() {
        return 'entity/' . $this->getEntityType() . '.yml';
    }

    public function __construct(ConfigAccumulator $config_accumulator, PlaceholderService $placeholder_service, $data)
    {
        parent::__construct($config_accumulator, $placeholder_service, $data);
        $this->bundle = $this->data['id'];
        $config = Utilities::mergeData($this->template, $this->getTemplateOverrideData());
        $this->configAccumulator->setConfig($this->getConfigName(), $config);
        $configs = $this->transformFields();
        foreach($configs as $key => $value) {
            $this->configAccumulator->setConfig($key, $value);
        }
    }

    public function getConfigName()
    {
        return $this->getEntityType() . '.type.' . $this->bundle;
    }

    protected function transformFields() {
        $data = $this->data;
        $field_configs = [];
        if (!empty($data['fields'])) {
            $weight = 0;
            $entityFormTransformer = new EntityForm($this->getEntityType(), $data, 'default');
            foreach ($data['fields'] as $key => $field) {
                $field['id'] = $key;
                $weight++;
                if (empty($field['weight'])) {
                    $field['weight'] = $weight;
                }
                $fieldStorageTransformer = new FieldStorage($this->getEntityType(), $field, $data);
                $this->injectDependency($fieldStorageTransformer);
                $this->configAccumulator->setConfig($fieldStorageTransformer->getConfigName(), $fieldStorageTransformer->getConfig());

                $fieldFieldTransformer = new FieldField($this->getEntityType(), $field, $data);
                $this->configAccumulator->setConfig($fieldFieldTransformer->getConfigName(), $fieldFieldTransformer->getConfig());

                $fieldWidgetTransformer = new FieldWidget($this->getEntityType(), $field, $data);
                $entityFormTransformer->attachField($fieldWidgetTransformer);
                $entityFormTransformer->setDependency('config', $fieldFieldTransformer->getConfigName($data['id']));
            }
            $entityFormTransformer->setDependency('config', $this->getConfigName($data['id']));
            $this->configAccumulator->setConfig($entityFormTransformer->getConfigName(), $entityFormTransformer->getConfig());
        }
        return $field_configs;
    }

    /**
     * @param \Phabalicious\Scaffolder\Transformers\Utils\FieldStorage $fieldStorageTransformer
     */
    protected function injectDependency(\Phabalicious\Scaffolder\Transformers\Utils\FieldStorage $fieldStorageTransformer)
    {
      if (!empty($this->getDependencies())) {
        foreach ($this->getDependencies() as $category => $dependencies) {
          if ($dependencies) {
            foreach ($dependencies as $dependency) {
              $fieldStorageTransformer->setDependency($category, $dependency);
            }
          }
        }
      }
    }
}
