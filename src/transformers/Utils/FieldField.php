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
        if ($this->getFieldBaseType() == 'entity_reference') {
          $out = $this->getTemplateOverrideDataForEntityReferences($out);
        }
        return $out;
    }

    private function getTemplateOverrideDataForEntityReferences($data)
    {
      $bundles = [];
      if (isset($this->data['bundles']) && !empty($this->data['bundles']) && is_array($this->data['bundles'])) {
        foreach ($this->data['bundles'] as $bundle) {
          $bundles[$bundle] = $bundle;
        }
      }
      switch($this->getFieldSubType()) {
        case 'node':
          if ($bundles) {
            $data['settings']['handler_settings']['target_bundles'] = $bundles;
            foreach ($bundles as $bundle) {
              $data['dependencies']['config'][] = 'node.type.' . $bundle;
            }
          }
          break;

        case 'taxonomy_term':
          if ($bundles) {
            $data['settings']['handler_settings']['target_bundles'] = $bundles;
            foreach ($bundles as $bundle) {
              $data['dependencies']['config'][] = 'taxonomy.vocabulary.' . $bundle;
            }
          }
          break;

        case 'media':
          if ($bundles) {
            $data['settings']['handler_settings']['target_bundles'] = $bundles;
            $data['settings']['handler_settings']['sort']['field'] = $this->getFieldName() . '.title';
            foreach ($bundles as $bundle) {
              $data['dependencies']['config'][] = 'media.type.' . $bundle;
            }
          }
          break;
      }
      return $data;
    }

}
