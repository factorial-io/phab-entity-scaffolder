<?php

namespace Phabalicious\Scaffolder\Transformers;

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Utilities\Utilities;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;
use Phabalicious\Scaffolder\Transformers\FieldFieldTransformer;

class EntityFormTransformer extends FieldTransformerBase
{

    protected $view_mode;

    protected function getTemplateFileName()
    {
        return 'core.entity_form_display.template.yml';
    }

    public function __construct($entity_type, $data, $view_mode)
    {
        $this->entity_type = $entity_type;
        $this->data = $data;
        $this->view_mode = $view_mode;
        $this->template = \Symfony\Component\Yaml\Yaml::parseFile($this->getTemplateFile());
        $this->result = Utilities::mergeData($this->template, $this->getTemplateOverrideData());
    }

    /**
     * Get the Drupal config name.
     */
    public function getConfigName()
    {
        // Format : 'core-entity_form_display-{entity_type}-{bundle}-{view_mode}'.
        return 'core.entity_form_display.' . $this->entity_type . '.' . $this->data['id'] . '.' . $this->view_mode;
    }

    public function transformDependend(): array
    {
        return [];
    }

    protected function getTemplateOverrideData($data=[])
    {
        return [
            'uuid' => PlaceholderService::PRESERVE_IF_AVAILABLE,
            // Format : '{entity_type}-{bundle}-{view_mode}'
            'id' => $this->entity_type . '.' . $this->data['id'] . '.' . $this->view_mode,
            'targetEntityType' => $this->entity_type,
            'mode' => $this->view_mode,
            'bundle' => $this->data['id'],
        ];
    }

    public function attachField(FieldFieldTransformer $fieldFieldTransformer) {
        $this->result['dependencies']['config'][] = $fieldFieldTransformer->getConfigName();
        $this->result['content'][$fieldFieldTransformer->getFieldName()] = [
            'placeholder'
        ];
    }
}
