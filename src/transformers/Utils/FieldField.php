<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Utilities\Utilities;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;
use Phabalicious\Scaffolder\Transformers\Utils\FieldBase;

class FieldField extends FieldBase
{

    protected function getTemplateFileName()
    {
        return 'field/' . $this->data['type']. '/field.yml';
    }

    /**
     * Get the Drupal config name.
     */
    public function getConfigName()
    {
        // Format : 'field-field-{entity_type}-{bundle}-{field_name}'.
        return 'field.field.' . $this->entity_type . '.' . $this->parent['id'] . '.' . $this->getFieldName();
    }

    protected function getTemplateOverrideData($data = [])
    {
        $out = [
            'uuid' => PlaceholderService::REUSE_OR_CREATE_VALUE,
            'id' => $this->entity_type . '.' . $this->parent['id'] . '.' . $this->getFieldName(),
            'field_name' => $this->getFieldName(),
            'bundle' => $this->parent['id'],
            'label' => !empty($this->data['label']) ? $this->data['label'] : $this->data['id'],
            'description' => !empty($this->parent['description']) ? $this->parent['description'] : '',
            'entity_type' => $this->entity_type,
            'required' => $this->data['required'] ?? false,
        ];
        if ($this->data['type'] == 'entity_reference') {
          $out = $this->getTemplateOverrideDataForEntityReferences($out);
        }
        return $out;
    }

    private function getTemplateOverrideDataForEntityReferences($data)
    {
      if (isset($this->data['bundles']) && !empty($this->data['bundles']) && is_array($this->data['bundles'])) {
        foreach ($this->data['bundles'] as $target) {
          $data['settings']['handler_settings']['target_bundles'][$target] = $target;
        }
      }
      switch($this->data['entity']) {
        case 'node':
          $data['settings']['handler'] = 'default:node';
          $data['dependencies']['config'][] = 'node.type.' . $target;
          break;

        case 'taxonomy_term':
          break;
      }
      return $data;
    }

}
