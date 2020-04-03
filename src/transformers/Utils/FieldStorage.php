<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Utilities\Utilities;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;
use Phabalicious\Scaffolder\Transformers\Utils\FieldBase;

class FieldStorage extends FieldBase
{

    protected function getTemplateFileName()
    {
        return 'field/' . $this->data['type']. '/storage.yml';
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
            'uuid' => PlaceholderService::REUSE_OR_CREATE_VALUE,
            'id' => $this->entity_type . '.' . $this->getFieldName(),
            'field_name' => $this->getFieldName(),
        ];
    }
}
