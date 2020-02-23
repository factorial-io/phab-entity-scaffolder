<?php

namespace Phabalicious\Scaffolder\Transformers;

class FieldFieldTransformer extends FieldTransformerBase
{

    public static function getName()
    {
        return 'field_field';
    }

    protected function getTemplateFileName() 
    {
        return 'field.field.template.' . $this->data['type'] . '.yml';
    }

    /**
     * Get the Drupal config name.
     */
    public function getConfigName() 
    {
        // Format : 'field-field-{entity_type}-{bundle}-{field_name}'.
        return 'field.field.' . $this->entity_type . '.' . $this->parent_data['id'] . '.' . $this->getFieldName();
    }

    public function transformDependend(): array
    {
        if (empty($this->parent_data['fields'])) {
            return [];
        }
        $result = Utilities::mergeData($this->template, $this->getTemplateOverrideData());
        $results[$this->getConfigName() . '.yml'] = $result;
        return $results;
    }

    protected function getTemplateOverrideData() {
        return [
            'uuid' => $this::PRESERVE_IF_AVAILABLE,
            'dependecies' => [
                'config' => [
                    // Format : '{entity_type}-type-{bundle}'
                    - $this->entity_type . '.type.' . $this->parent_data['id'],
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
