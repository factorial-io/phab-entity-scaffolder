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
        $out = [
            'uuid' => PlaceholderService::REUSE_OR_CREATE_VALUE,
            'id' => $this->entity_type . '.' . $this->getFieldName(),
            'field_name' => $this->getFieldName(),
        ];
        if ($this->data['type'] == 'entity_reference') {
          $out = $this->getTemplateOverrideDataForEntityReferences($out);
        }
        return $out;
    }

  private function getTemplateOverrideDataForEntityReferences($data)
  {
    switch($this->data['entity']) {
      case 'node':
        $data['dependencies']['module'][] = 'node';
        $data['settings']['target_type'] = 'node';
        break;

      case 'taxonomy_term':
        $data['dependencies']['module'][] = 'taxonomy_term';
        $data['settings']['target_type'] = 'taxonomy_term';
        break;
    }
    return $data;
  }
}
