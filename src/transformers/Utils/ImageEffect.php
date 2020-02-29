<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;
use Phabalicious\Utilities\Utilities;
use Phabalicious\Scaffolder\Transformers\Utils\FieldBase;
use Symfony\Component\Yaml\Yaml;

class ImageEffect extends EntityPropertyTransformerBase
{
    protected $effect;
    protected $height;
    protected $width;
    protected $data;
    protected $config;

    public function __construct($data)
    {
      $this->effect = $data['effect'];
      $this->height = $data['effective_height'];
      $this->width = $data['effective_width'];
      $this->placeholderService = new PlaceholderService();
      $this->template = Yaml::parseFile($this->getTemplateFile())['effects'][$this->effect];
      $config = Utilities::mergeData($this->template, $this->getTemplateOverrideData());
      $this->setConfig($config);
    }

    protected function getTemplateFileName()
    {
        return 'image/effects.yml';
    }

    protected function getTemplateOverrideData()
    {
        $data['uuid'] = $this->placeholderService->generateUUID();
        if (!empty($this->height)) {
            $data['data']['height'] = $this->height;
        }
        if (!empty($this->width)) {
            $data['data']['width'] = $this->width;
        }
        return $data;
    }


    public function getDependencies()
    {
        if ($this->effect == 'focal_point_scale_and_crop') {
            return [
                'module' => [
                    'focal_point'
                ]
            ];
        }
        else {
            return [];
        }
    }

}
