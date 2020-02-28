<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Utilities\Utilities;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;
use Phabalicious\Scaffolder\Transformers\Utils\EntityPropertyTransformerBase;

abstract class FieldTransformerBase extends EntityPropertyTransformerBase
{
    protected $entity_type;
    protected $data;
    protected $parent;

    protected function getTemplateDir() 
    {
        return __DIR__ . '/templates';
    }

    public function __construct($entity_type, $data, $parent)
    {
        $this->entity_type = $entity_type;
        $this->data = $data;
        $this->parent = $parent;
        $this->template = \Symfony\Component\Yaml\Yaml::parseFile($this->getTemplateFile());
        $config = Utilities::mergeData($this->template, $this->getTemplateOverrideData());
        $this->setConfig($config);
    }

    public function getFieldName() {
        // @TODO Check if field_ prefix can be dropped in D8 too.
        return 'field_' . $this->parent['id'] . '_' . $this->data['id'];
    }

}
