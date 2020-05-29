<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

class FieldStorage extends FieldBase
{

    protected function getTemplateFileName()
    {
        return 'field/' . $this->getFieldType(). '/storage.yml';
    }

    /**
     * Get the Drupal config name.
     */
    public function getConfigName()
    {
        // Format : 'field-storage-{entity_type}-{field_name}'.
        return 'field.storage.' . $this->entity_type . '.' . $this->getFieldName();
    }

    protected function getTemplateOverrideData()
    {
        $out = [
            'uuid' => PlaceholderService::REUSE_OR_CREATE_VALUE,
            'id' => $this->entity_type . '.' . $this->getFieldName(),
            'field_name' => $this->getFieldName(),
            'cardinality' => $this->getDataValue('cardinality', 1),
            'entity_type' => $this->entity_type,
        ];
        return $out;
    }
}
