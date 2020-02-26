<?php

namespace Phabalicious\Scaffolder\Transformers;

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Utilities\Utilities;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;

class FieldStorageTransformer extends FieldTransformerBase
{

    protected function getTemplateFileName() 
    {
        return 'field.storage.template.' . $this->data['type'] . '.yml';
    }

    /**
     * Get the Drupal config name.
     */
    public function getConfigName()
    {
        // Format : 'field-storage-{entity_type}-{field_name}'.
        return 'field.storage.' . $this->entity_type . '.' . $this->getFieldName();
    }

    public function transformDependend(): array
    {
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
                'module' => [
                    $this->entity_type,
                ]
            ],
            'id' => $this->entity_type . '.' . $this->getFieldName(),
            'field_name' => $this->getFieldName(),
        ];
    }

}
