<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Utilities\Utilities;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;
use Phabalicious\Scaffolder\Transformers\Utils\FieldBase;

class FieldWidget extends FieldBase
{

    public function __construct($entity_type, $data, $parent)
    {
        $this->entity_type = $entity_type;
        $this->data = $data;
        $this->parent = $parent;
        $this->template = \Symfony\Component\Yaml\Yaml::parseFile($this->getTemplateFile());
        $config = [];
        foreach ($this->template['content'] as $view_mode => $template) {
            $config['content'][$view_mode] = Utilities::mergeData($template, $this->getTemplateOverrideData());
        }
        $this->setConfig($config);
    }

    protected function getTemplateFileName()
    {
        return 'field/' . $this->data['type']. '/form.yml';
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
