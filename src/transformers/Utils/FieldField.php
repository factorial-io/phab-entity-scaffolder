<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

class FieldField extends FieldBase
{


    protected function getTemplateFileName()
    {
        return 'field/' .$this->getFieldType(). '/field.yml';
    }

    /**
     * Get the Drupal config name.
     */
    public function getConfigName()
    {
        // Format : 'field-field-{entity_type}-{bundle}-{field_name}'.
        return 'field.field.' . $this->entity_type . '.' . $this->parent['id'] . '.' . $this->getFieldName();
    }

    protected function getTemplateOverrideData()
    {
        $out = [
            'uuid' => PlaceholderService::REUSE_OR_CREATE_VALUE,
            'id' => $this->entity_type . '.' . $this->parent['id'] . '.' . $this->getFieldName(),
            'field_name' => $this->getFieldName(),
            'bundle' => $this->parent['id'],
            'label' => !empty($this->data['label']) ? $this->data['label'] : $this->data['id'],
            'description' => $this->getDescValue(),
            'entity_type' => $this->entity_type,
            'required' => $this->getDataValue('required', false),
        ];

        return $out;
    }
}
