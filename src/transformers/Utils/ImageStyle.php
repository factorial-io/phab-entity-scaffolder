<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Scaffolder\Transformers\Utils\Base;
use Phabalicious\Scaffolder\Transformers\Utils\ImageEffect;
use Phabalicious\Utilities\Utilities;

class ImageStyle extends Base
{

    protected $imageEffect;

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

        if (!(empty($width) || empty($height))) {
            $effect = 'focal_point_scale_and_crop';
        } else {
            $effect = 'image_scale';
        }

        $data = [
          'width' => $width,
          'height' => $height,
          'effect' => $effect,
          'multiplier' => $multiplier,
          'effective_width' => $effective_width,
          'effective_height' => $effective_height,
        ];
        $data['name'] = $this->generateStyleName($data);
        parent::__construct($config_accumulator, $placeholder_service, $data);
        // We can hrdcode the position to 0, as we will only have one imageeffect per style.
        $this->imageEffect = new ImageEffect($data, 0);
        $config = Utilities::mergeData($this->template, $this->getTemplateOverrideData());
        $config['effects'][PlaceholderService::createChildReference('uuid')] = $this->imageEffect->getConfig();
        $this->configAccumulator->setConfig($this->getConfigName(), $config);
        $this->addDependencyFromImageEffects();
    }
    public function getName()
    {
        return $this->data['name'];
    }
    protected function generateStyleName($data)
    {
        $prefix = 'esimg_';
        $width = $data['effective_width'];
        $height = $data['effective_height'];
        if (empty($width) & !empty($height)) {
            $name = $height . 'h';
        } elseif (!empty($width) & empty($height)) {
            $name = $width . 'w';
        } else {
            $name = $width . 'x' . $height;
        }
        return $prefix . $name;
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
        return $this->imageEffect->getDependencies();
    }

    private function addDependencyFromImageEffects()
    {
        if ($this->imageEffect->getDependencies()) {
            foreach ($this->imageEffect->getDependencies() as $category => $dependencies) {
                foreach ($dependencies as $dependency) {
                    $this->configAccumulator->addDependency($this->getConfigName(), $category, $dependency);
                }
            }
        }
    }
}
