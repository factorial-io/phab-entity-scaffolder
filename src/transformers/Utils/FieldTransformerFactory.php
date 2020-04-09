<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

class FieldTransformerFactory
{

    protected static $aliases = [
        'link' => 'cta',
    ];

    protected static $storageFactory = [
        'default' => FieldStorage::class,
    ];
    protected static $fieldFactory = [
        'cta' => Plugins\CtaFieldField::class,
        'entity_reference' => Plugins\EntityReferenceFieldField::class,
        'default' => FieldField::class,
    ];
    protected static $widgetFactory = [
        'default' => FieldWidget::class,
    ];
    protected static $formatterFactory = [
        'default' => FieldFormatter::class,
        'entity_reference' => Plugins\EntityReferenceFieldFormatter::class,
    ];

    /**
     * Create all necessary transformers for a given field type.
     *
     * @param $entity_type
     * @param  array  $field
     * @param  array  $data
     *
     * @return FieldTransformerContainer
     */
    public static function create($entity_type, array $field, array $data)
    {
        $field['type'] = self::resolveAlias($field['type']);
        $field_base_type = self::getFieldBaseType($field['type']);

        return new FieldTransformerContainer(
            self::lookup(self::$storageFactory, $field_base_type),
            self::lookup(self::$fieldFactory, $field_base_type),
            self::lookup(self::$widgetFactory, $field_base_type),
            self::lookup(self::$formatterFactory, $field_base_type),
            $entity_type,
            $field,
            $data
        );
    }

    protected static function lookup($classes, $field_type)
    {
        return $classes[$field_type] ?? $classes['default'];
    }

    protected static function resolveAlias($field_type)
    {

        $base = self::getFieldBaseType($field_type);
        $sub = self::getFieldSubType($field_type, false);

        $base = self::$aliases[$base] ?? $base;

        return $sub ? "$base/$sub" : $base;
    }

    public static function getFieldBaseType(string $field_type)
    {
        list($base, ) = array_pad(explode('/', $field_type), 2, null);
        return $base;
    }
    
    public static function getFieldSubType(string $field_type, $default = 'default')
    {
        list(, $sub) = array_pad(explode('/', $field_type), 2, $default);
        return $sub;
    }
}
