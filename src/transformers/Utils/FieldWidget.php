<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Utilities\Utilities;

class FieldWidget extends FieldBase
{

    public function __construct($entity_type, $data, $parent)
    {
        $this->entity_type = $entity_type;
        $this->data = $data;
        $this->parent = $parent;
        $this->template = PlaceholderService::parseTemplateFile($this->getTemplateFile());
        $config = [];
        foreach ($this->template['content'] as $view_mode => $template) {
            $config['content'][$view_mode] = Utilities::mergeData($template, $this->getTemplateOverrideData());
        }
        $this->setConfig($config);
    }

    protected function getTemplateFileName()
    {
        return 'field/' . $this->getFieldType(). '/form.yml';
    }

    protected function getTemplateOverrideData()
    {
        return [
            'weight' => $this->data['weight'],
        ];
    }

    public function getSpecificConfig($widget = 'default')
    {
        return $this->getConfig()['content'][$widget];
    }

    public function getSpecificDependencies($widget = 'default')
    {
        return $this->getConfig()['dependencies'][$widget] ?? [];
    }
}
