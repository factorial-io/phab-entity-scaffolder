<?php


namespace Phabalicious\Scaffolder\Transformers\Utils;

class FieldTransformerContainer
{

    protected $storage;
    protected $field;
    protected $widget;
    protected $formatter;

    /**
     * FieldTransformerContainer constructor.
     * @param $storage_class
     * @param $field_class
     * @param $widget_class
     * @param $formatter_class
     * @param $entity_type
     * @param $field
     * @param $data
     */
    public function __construct(
        $storage_class,
        $field_class,
        $widget_class,
        $formatter_class,
        $entity_type,
        array $field,
        array $data
    ) {
        $this->storage = new $storage_class($entity_type, $field, $data);
        $this->field = new $field_class($entity_type, $field, $data);
        $this->widget = new $widget_class($entity_type, $field, $data);
        $this->formatter = new $formatter_class($entity_type, $field, $data);
    }

    public function getStorage(): FieldStorage
    {
        return $this->storage;
    }

    public function getField(): FieldField
    {
        return $this->field;
    }
    public function getWidget(): FieldWidget
    {
        return $this->widget;
    }

    public function getFormatter(): FieldFormatter
    {
        return $this->formatter;
    }
}
