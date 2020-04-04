<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Utilities\Utilities;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;
use Phabalicious\Scaffolder\Transformers\Utils\FieldField;
use Phabalicious\Scaffolder\Transformers\Utils\FieldWidget;
use Phabalicious\Scaffolder\Transformers\Utils\FieldBase;

class EntityView extends EntityPropertyBase
{

    protected $view_mode;
    private $entity_type;
    private $data;

    protected function getTemplateFileName()
    {
        return 'entity_view_display/template.yml';
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
        // Format : 'core-entity_view_display-{entity_type}-{bundle}-{view_mode}'.
        return 'core.entity_view_display.' . $this->entity_type . '.' . $this->data['id'] . '.' . $this->view_mode;
    }

    protected function getTemplateOverrideData($data = [])
    {
        $out = [
            'uuid' => PlaceholderService::REUSE_OR_CREATE_VALUE,
            // Format : '{entity_type}-{bundle}-{view_mode}'
            'id' => $this->entity_type . '.' . $this->data['id'] . '.' . $this->view_mode,
            'targetEntityType' => $this->entity_type,
            'mode' => $this->view_mode,
            'bundle' => $this->data['id'],
        ];
        return $out;
    }

    public function attachField(FieldFormatter $fieldFormatterTransformer)
    {
        $fieldname = $fieldFormatterTransformer->getFieldName();
        $this->config['content'][$fieldname] = $fieldFormatterTransformer->getSpecificConfig();
        // Adding dependencies if any, from template.
        $dependencies = $fieldFormatterTransformer->getSpecificDependencies();
        if ($dependencies) {
            foreach ($dependencies as $category) {
                foreach ($category as $config_name) {
                    $this->setDependency($category, $config_name);
                }
            }
        }
    }
}
