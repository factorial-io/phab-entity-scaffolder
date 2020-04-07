<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Utilities\Utilities;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;
use Phabalicious\Scaffolder\Transformers\Utils\EntityPropertyBase;
use Symfony\Component\Yaml\Yaml;

abstract class FieldBase extends EntityPropertyBase
{
    protected $entity_type;
    protected $data;
    protected $parent;
    
    const FIELD_ALIAS_MAP = [
        'link' => 'cta'
    ];

    public function __construct($entity_type, $data, $parent)
    {
        $this->entity_type = $entity_type;
        $this->data = $data;
        $this->parent = $parent;
        $this->template = Yaml::parseFile($this->getTemplateFile());
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
        return self::FIELD_ALIAS_MAP[$this->data['type']] ?? $this->data['type'];
    }

    protected function getFieldBaseType()
    {
        return explode('/', $this->getFieldType())[0] ?? null;
    }

    protected function getFieldSubType()
    {
        return explode('/', $this->getFieldType())[1] ?? 'default';
    }

    protected function getTemplateDir()
    {
        return __DIR__ . '/templates';
    }


    public function getFieldName()
    {
        // @TODO Check if field_ prefix can be dropped in D8 too.
        return 'field_' . $this->parent['id'] . '_' . $this->data['id'];
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
