<?php

namespace Phabalicious\Scaffolder\Transformers;

class FieldStorageTransformer extends FieldTransformerBase
{

    public static function getName()
    {
        return 'field_storage';
    }

    protected function getTemplateFileName() 
    {
        return 'field.storage.template.' . $this->data['type'] . '.yml';
    }

    /**
     * Get the Drupal config name.
     */
    public function getConfigName() 
    {
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
                'module' => [
                    $this->entity_type,
                ]
            ],
            'id' => $this->entity_type . '.' . $this->getFieldName(),
            'field_name' => $this->getFieldName(),
        ];
    }

}
