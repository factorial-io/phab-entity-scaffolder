<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Scaffolder\Transformers\Utils\Base;
use Phabalicious\Scaffolder\Transformers\Utils\ImageEffect;
use Phabalicious\Utilities\Utilities;

class ImageStyleOcc extends Base
{

    protected $imageEffect;
    protected $imageEffects = [];

    public function __construct(ConfigAccumulator $config_accumulator, PlaceholderService $placeholder_service, $data)
    {
        $multiplier = 1;
        if (!empty($data['multiplier'])) {
            $multiplier = trim($data['multiplier'], ' xX.');
        }
        $effect = null;
        $width = null;
        $height = null;
        $effective_width = null;
        $effective_height = null;

        if (!empty($data['width'])) {
            $width = $data['width'];
            $effective_width = intval($width * $multiplier, 10);
        }

        if (!empty($data['height'])) {
            $height = $data['height'];
            $effective_height = intval($height * $multiplier, 10);
        }
        $data = [
            'effect' => 'crop_crop',
            'crop_type' => $data['crop_type'],
            'style_id' => $data['style_id'],
            'width' => $width,
            'height' => $height,
            'multiplier' => $multiplier,
            'effective_width' => $effective_width,
            'effective_height' => $effective_height,
        ];
        $data['name'] =  $this->generateStyleName($data);
        $this->data = $data;
        $config = Utilities::mergeData($this->template, $this->getTemplateOverrideData());
        parent::__construct($config_accumulator, $placeholder_service, $data);
        // We can hrdcode the position to 0, as we will only have one imageeffect per style.
        $effect = new ImageEffect($data, 0);
        $config['effects'][PlaceholderService::createChildReference('uuid')] = $effect->getConfig();
        $this->imageEffects[] = $effect;
        $this->addDependencyFromImageEffects();
        $data['effect'] = 'image_scale_and_crop';
        $this->data = $data;
        // We can hrdcode the position to 0, as we will only have one imageeffect per style.
        $effect = new ImageEffect($data, 1);
        $config['effects'][PlaceholderService::createChildReference('uuid')] = $effect->getConfig();
        $this->configAccumulator->setConfig($this->getConfigName(), $config);
        $this->imageEffects[] = $effect;
        $this->addDependencyFromImageEffects();
    }
    
    public function getSize()
    {
        return $this->data['width'] ?? $this->data['height'];
    }

    public function getName()
    {
        return $this->data['name'];
    }

    protected function generateStyleName($data)
    {
        return $data['style_id'];
    }

    protected function getTemplateFileName()
    {
        return 'image/style.template.yml';
    }

    protected function getConfigName()
    {
        return 'image.style.' . $this->getName();
    }

    protected function getTemplateOverrideData()
    {
        // @TODO Fill $data with existing template data.
        $out = [];
        $out['name'] = $this->getName();
        $out['label'] = $this->getName();
        $out['uuid'] = PlaceholderService::REUSE_OR_CREATE_VALUE;
        return $out;
    }

    public function getDependencies()
    {
        $d = [];
        foreach ($this->imageEffects as $effect) {
            $d += $effect->getDependencies();
        }
        return $d;
    }

    private function addDependencyFromImageEffects()
    {
        foreach ($this->imageEffects as $effect) {
            if ($effect->getDependencies()) {
                foreach ($effect->getDependencies() as $category => $dependencies) {
                    foreach ($dependencies as $dependency) {
                        $this->configAccumulator->addDependency($this->getConfigName(), $category, $dependency);
                    }
                }
            }
        }

    }
}
