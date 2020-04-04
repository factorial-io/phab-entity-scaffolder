<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Utilities\Utilities;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;
use Phabalicious\Scaffolder\Transformers\Utils\FieldField;
use Phabalicious\Scaffolder\Transformers\Utils\FieldWidget;
use Phabalicious\Scaffolder\Transformers\Utils\FieldBase;

class EntityForm extends EntityPropertyBase
{

    protected $view_mode;
    private $entity_type;
    private $data;

    protected function getTemplateFileName()
    {
        return 'entity_form_display/template.yml';
    }

    public function __construct($entity_type, $data, $view_mode)
    {
        $this->entity_type = $entity_type;
        $this->data = $data;
        $this->view_mode = $view_mode;
        $this->template = \Symfony\Component\Yaml\Yaml::parseFile($this->getTemplateFile());
        $result = Utilities::mergeData($this->template, $this->getTemplateOverrideData());
        $this->setConfig($result);
    }

    /**
     * Get the Drupal config name.
     */
    public function getConfigName()
    {
        // Format : 'core-entity_form_display-{entity_type}-{bundle}-{view_mode}'.
        return 'core.entity_form_display.' . $this->entity_type . '.' . $this->data['id'] . '.' . $this->view_mode;
    }

    protected function getTemplateOverrideData($data = [])
    {
        return [
            'uuid' => PlaceholderService::REUSE_OR_CREATE_VALUE,
            // Format : '{entity_type}-{bundle}-{view_mode}'
            'id' => $this->entity_type . '.' . $this->data['id'] . '.' . $this->view_mode,
            'targetEntityType' => $this->entity_type,
            'mode' => $this->view_mode,
            'bundle' => $this->data['id'],
        ];
    }

    public function attachField(FieldWidget $fieldWidgetTransformer)
    {
        $this->config['content'][$fieldWidgetTransformer->getFieldName()] = $fieldWidgetTransformer->getSpecificConfig();
        // Adding dependencies if any, from template.
        $dependencies = $fieldWidgetTransformer->getSpecificDependencies();
        if ($dependencies) {
            foreach($dependencies as $category) {
                foreach($category as $config_name) {
                  $this->setDependency($category, $config_name);
                }
            }
        }
    }
}
