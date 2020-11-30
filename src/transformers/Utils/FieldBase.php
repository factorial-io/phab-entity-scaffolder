<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Utilities\Utilities;

abstract class FieldBase extends EntityPropertyBase
{
    protected $entity_type;
    protected $data;
    protected $parent;
    

    public function __construct($entity_type, $data, $parent)
    {
        $this->entity_type = $entity_type;
        $this->data = $data;
        $this->parent = $parent;
        $this->template = PlaceholderService::parseTemplateFile($this->getTemplateFile());
        $config = Utilities::mergeData($this->template, $this->getTemplateOverrideData());
        $this->setConfig($config);
    }

    /**
     * Get the field type.
     *
     * @return string
     */
    public function getFieldType()
    {
        return $this->data['type'];
    }

    protected function getFieldBaseType()
    {
        return FieldTransformerFactory::getFieldBaseType($this->getFieldType());
    }

    protected function getFieldSubType()
    {
        return FieldTransformerFactory::getFieldSubType($this->getFieldType());
    }

    protected function getTemplateDir()
    {
        return __DIR__ . '/templates';
    }


    public function getFieldName()
    {
        // Get fieldname from yaml to support fields on existing entities.
        if (isset($this->data['field_name'])) {
            $field_name = $this->data['field_name'];
        } else {
            $field_name = 'field_' . $this->parent['id'] . '_' . $this->data['id'];
        }

        if (strlen($field_name) > 32) {
            $a = explode('_', $field_name);
            for ($i = 2; $i < count($a) - 1; $i++) {
                $a[$i] = substr($a[$i], 0, 4);
            }
            $field_name = implode("_", $a);
        }
        // If still too long, raise an exception
        if (strlen($field_name) > 32) {
            throw new \RuntimeException(sprintf("Fieldname %s is too long, please use sth shorter!", $field_name));
        }
        return $field_name;
    }

    /**
     * Will return a value from the data array. If not available the default
     * will be picked up from the template. As a last resort, the provided default
     * value will be returned.
     *
     * @param $key
     *   The name of the key.
     * @param $default
     *   The default value if key is not present in data nor template.
     *
     * @return mixed
     */
    public function getDataValue($key, $default)
    {
        return $this->data[$key] ?? $this->template[$key] ?? $default;
    }
}
