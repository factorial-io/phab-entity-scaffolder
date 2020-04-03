<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Scaffolder\Transformers\Utils\FieldBase;
use Phabalicious\Utilities\Utilities;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;

class FieldFormatter extends FieldBase
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
    return 'field/' . $this->data['type']. '/view.yml';
  }

  protected function getTemplateOverrideData()
  {
    $out = [
      'weight' => $this->data['weight'],
    ];
    switch ($this->data['type']) {
      case 'entity_reference':
        $out['settings']['view_mode'] = $this->data['view_mode'] ?? 'default';
        break;
    }
    return $out;
  }

  public function getSpecificConfig($formatter = 'default')
  {
    return $this->getConfig()['content'][$formatter];
  }
}
