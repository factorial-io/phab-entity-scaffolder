<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Utilities\Utilities;

class ImageEffect extends EntityPropertyBase
{
    protected $effect;
    protected $height;
    protected $width;
    protected $data;
    protected $config;

    protected $position;

    public function __construct($data, $position)
    {
        $this->position = $position;
        $this->effect = $data['effect'];
        $this->height = $data['effective_height'];
        $this->width = $data['effective_width'];
        $this->placeholderService = new PlaceholderService();
        $this->template = PlaceholderService::parseTemplateFile($this->getTemplateFile())['effects'][$this->effect];
        $config = Utilities::mergeData($this->template, $this->getTemplateOverrideData());
        $this->setConfig($config);
    }

    protected function getTemplateFileName()
    {
        return 'image/effects.yml';
    }

    protected function getTemplateOverrideData()
    {
        $data['uuid'] = PlaceholderService::createAbsoluteReuseReference(['effects', $this->position, 'uuid']);
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
        } else {
            return [];
        }
    }
}
