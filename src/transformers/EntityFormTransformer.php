<?php

namespace Phabalicious\Scaffolder\Transformers;

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Utilities\Utilities;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;

class EntityFormTransformer extends FieldTransformerBase
{

    public static function getName()
    {
        return 'entity_form';
    }

    protected function getTemplateFileName()
    {
        return 'field.field.template.' . $this->data['type'] . '.yml';
    }

    /**
     * Get the Drupal config name.
     */
    public function getConfigName($id = '')
    {
        // Format : 'field-field-{entity_type}-{bundle}-{field_name}'.
        return 'field.field.' . $this->entity_type . '.' . $this->parent['id'] . '.' . $this->getFieldName();
    }

    public function transformDependend(): array
    {
        // TODO check input params.
        if (empty($this->parent['fields'])) {
            return [];
        }
        $result = Utilities::mergeData($this->template, $this->getTemplateOverrideData());
        $results[$this->getConfigName() . '.yml'] = $result;
        return $results;
    }

    protected function getTemplateOverrideData($data=[])
    {
        return [
            'uuid' => PlaceholderService::PRESERVE_IF_AVAILABLE,
            'dependecies' => [
                'config' => [
                    // Format : '{entity_type}-type-{bundle}'
                    - $this->entity_type . '.type.' . $this->parent['id'],
                    // Format : 'field-storage-{entity_type}-{field_name}'
                    - 'field.storage.' . $this->entity_type . '.' . $this->getFieldName()
                ]
            ],
            // Format : '{entity_type}-{bundle}-{field_name}'
            'id' => $this->entity_type . '.' . $this->parent['id'] . '.' . $this->getFieldName(),
            'field_name' => $this->getFieldName(),
            'bundle' => $this->parent['id'],
            'label' => !empty($this->parent['label']) ? $this->parent['label'] : $this->parent['id'],
            'description' => !empty($this->parent['description']) ? $this->parent['description'] : '',
            'entity_type' => $this->entity_type,
            'required' => !!empty($this->data['required']),
        ];
    }

}
