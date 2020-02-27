<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Utilities\Utilities;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;
use Phabalicious\Scaffolder\Transformers\Utils\FieldTransformerBase;

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

    protected function getTemplateOverrideData() 
    {
        return [
            'uuid' => PlaceholderService::PRESERVE_IF_AVAILABLE,
            'id' => $this->entity_type . '.' . $this->getFieldName(),
            'field_name' => $this->getFieldName(),
        ];
    }

}
