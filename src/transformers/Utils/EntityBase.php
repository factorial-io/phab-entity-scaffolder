<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Exception\ValidationFailedException;
use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Scaffolder\Transformers\Utils\FieldWidget;
use Phabalicious\Utilities\Utilities;
use Phabalicious\Scaffolder\Transformers\Utils\ConfigAccumulator;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;
use Phabalicious\Scaffolder\Transformers\Utils\Base;
use Phabalicious\Scaffolder\Transformers\Utils\EntityForm;
use Phabalicious\Scaffolder\Transformers\Utils\FieldField;
use Phabalicious\Scaffolder\Transformers\Utils\FieldStorage;
use Phabalicious\Validation\ValidationErrorBag;
use Phabalicious\Validation\ValidationService;

abstract class EntityBase extends Base
{
    protected $bundle;

    public function getEntityType()
    {
        throw new \Exception('Missing getEntityType method in concrete class');
    }

    public function getTemplateFileName()
    {
        return 'entity/' . $this->getEntityType() . '.yml';
    }

    public function __construct(ConfigAccumulator $config_accumulator, PlaceholderService $placeholder_service, $data)
    {
        parent::__construct($config_accumulator, $placeholder_service, $data);
        $errors = new ValidationErrorBag();
        $service = new ValidationService($this->data, $errors, 'entity');
        $service->hasKeys([
            "label" => "Entity needs a label",
            "fields" => "Entity needs a list of fields"
        ]);
        if ($errors->hasErrors()) {
            throw new ValidationFailedException($errors);
        }
        
        if (empty($this->data['id'])) {
            $this->data['id']  = Utilities::slugify($this->data['label'], '_');
        }
        $this->bundle = $this->data['id'];
        $config = Utilities::mergeData($this->template, $this->getTemplateOverrideData());
        $this->configAccumulator->setConfig($this->getConfigName(), $config);
        $configs = $this->transformFields();
        foreach ($configs as $key => $value) {
            $this->configAccumulator->setConfig($key, $value);
        }
    }

    public function getConfigName()
    {
        return $this->getEntityType() . '.type.' . $this->bundle;
    }

    protected function transformFields()
    {
        $data = $this->data;
        $field_configs = [];
        if (!empty($data['fields'])) {
            $weight = 0;
            $entityFormTransformer = new EntityForm($this->getEntityType(), $data, 'default');
            $entityViewTransformer = new EntityView($this->getEntityType(), $data, 'default');
            foreach ($data['fields'] as $key => $field) {
                $field['id'] = $key;
                $weight++;
                if (empty($field['weight'])) {
                    $field['weight'] = $weight;
                }

                $fieldTransformer = FieldTransformerFactory::create($this->getEntityType(), $field, $data);

                $this->injectDependency($fieldTransformer->getStorage());
                $this->configAccumulator->setConfig(
                    $fieldTransformer->getStorage()->getConfigName(),
                    $fieldTransformer->getStorage()->getConfig()
                );

                $fieldTransformer->getField()->setDependency('config', $this->getConfigName());
                $fieldTransformer->getField()->setDependency(
                    'config',
                    $fieldTransformer->getStorage()->getConfigName()
                );
                $this->configAccumulator->setConfig(
                    $fieldTransformer->getField()->getConfigName(),
                    $fieldTransformer->getField()->getConfig()
                );

                $entityFormTransformer->attachField($fieldTransformer->getWidget());
                $entityFormTransformer->setDependency(
                    'config',
                    $fieldTransformer->getField()->getConfigName()
                );

                $entityViewTransformer->attachField($fieldTransformer->getFormatter());
                $entityViewTransformer->setDependency(
                    'config',
                    $fieldTransformer->getField()->getConfigName()
                );
            }
            $entityFormTransformer->setDependency('config', $this->getConfigName());
            $this->configAccumulator->setConfig(
                $entityFormTransformer->getConfigName(),
                $entityFormTransformer->getConfig()
            );

            $entityViewTransformer->setDependency('config', $this->getConfigName());
            $this->configAccumulator->setConfig(
                $entityViewTransformer->getConfigName(),
                $entityViewTransformer->getConfig()
            );
        }
        return $field_configs;
    }

    /**
     * @param \Phabalicious\Scaffolder\Transformers\Utils\FieldStorage $fieldStorageTransformer
     */
    protected function injectDependency(FieldStorage $fieldStorageTransformer)
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
