<?php

namespace Phabalicious\Scaffolder\Transformers;

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Utilities\Utilities;

require_once __DIR__ . '/EntityScaffolderTransformerBase.php';

abstract class FieldTransformerBase extends EntityScaffolderTransformerBase
{
    protected $entity_type;
    protected $data;
    protected $parent_data;

    public function __construct($entity_type = '', $data = [], $parent_data = [])
    {
        if (empty($entity_type)) {
            return;
        }
        $this->entity_type = $entity_type;
        $this->data = $data;
        $this->parent_data = $parent_data;
        $this->template = \Symfony\Component\Yaml\Yaml::parseFile($this->getTemplateFile());
    }

    protected function getFieldName() {
        // @TODO Check if field_ prefix can be dropped in D8 too.
        return 'field_' . $this->parent_data['id'] . '_' . $this->data['id'];
    }

    public function transform(TaskContextInterface $context, array $files): array
    {
        // We would not be receiving direct field definitions.
        return [];
    }

    public function transformDependend(): array
    {
        $result = Utilities::mergeData($this->template, $this->getTemplateOverrideData($data));
        $results[$this->getTemplateFileName($data['id'])] = $result;
        return $results;
    }

    protected function getTemplateOverrideData($data=[])
    {
        return [
            'uuid' => $this::PRESERVE_IF_AVAILABLE,
        ];
    }

}
