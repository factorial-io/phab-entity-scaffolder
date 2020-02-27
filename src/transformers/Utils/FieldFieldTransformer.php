<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Utilities\Utilities;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;
use Phabalicious\Scaffolder\Transformers\Utils\FieldTransformerBase;


class FieldFieldTransformer extends FieldTransformerBase
{

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
        return 'field.field.' . $this->entity_type . '.' . $this->parent['id'] . '.' . $this->getFieldName();
    }

    protected function getTemplateOverrideData($data=[])
    {
        return [
            'uuid' => PlaceholderService::PRESERVE_IF_AVAILABLE,
            // 'dependecies' => [
            //     'config' => [
            //         // Format : '{entity_type}-type-{bundle}'
            //         - $this->entity_type . '.type.' . $this->parent['id'],
            //         // Format : 'field-storage-{entity_type}-{field_name}'
            //         - 'field.storage.' . $this->entity_type . '.' . $this->getFieldName()
            //     ]
            // ],
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
