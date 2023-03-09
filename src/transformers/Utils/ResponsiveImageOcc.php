<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Scaffolder\Transformers\Utils\Base;
use Phabalicious\Scaffolder\Transformers\Utils\ImageEffect;
use Phabalicious\Utilities\Utilities;

class ResponsiveImageOcc extends Base
{

    public function __construct(ConfigAccumulator $config_accumulator, PlaceholderService $placeholder_service, $data)
    {
        parent::__construct($config_accumulator, $placeholder_service, $data);
        $config = Utilities::mergeData($this->template, $this->getTemplateOverrideData());
        $this->configAccumulator->setConfig($this->getConfigName(), $config);
        $this->transformImageStyles();
    }

    protected function transformImageStyles()
    {
        $fallback_image_style = $automatic_fallback_image_style = false;
        $data = $this->data;
        $image_style_mappings = [];
        $styleTransformers = [];
        $multipliers = ['1x'];
        if (!empty($data['multipliers'])) {
            $multipliers = $data['multipliers'];
        }
        $max_size = PHP_INT_MAX;
        $crop = $data['crop'];
        [$ratio_h, $ratio_w] = explode('x', $crop);
        foreach ($multipliers as $multiplier) {
            if (is_array($data['mapping'])) {
                foreach ($data['mapping'] as $breakpoint => $style_data) {
                    $style_data['multiplier'] = $multiplier;
                    $width = $style_data['width'];
                    $style_data['height'] = intval($width * $ratio_h / $ratio_w);
                    $style_data['crop_type'] = $crop;
                    $style_data['style_id'] = $this->getStyleIdentifierOccCropStyle($data['id'], $breakpoint, $multiplier);
                    if ($this->data['type'] == 'manual_crop') {
                        $styleTransformer = new ImageStyleOcc(
                            $this->configAccumulator,
                            $this->placeholderService,
                            $style_data
                        );
                    }
                    else {
                        $styleTransformer = new ImageStyle(
                            $this->configAccumulator,
                            $this->placeholderService,
                            $style_data
                        );
                    }



                    $styleTransformers[$multiplier][$breakpoint] = $styleTransformer;
                    $image_style_mappings[] = [
                        'breakpoint_id' => $this->data['breakpoint_group'].'.'.$breakpoint,
                        'multiplier' => $multiplier,
                        'image_mapping_type' => 'image_style',
                        'image_mapping' => $styleTransformer->getName(),
                    ];
                    if (!empty($style_data['fallback']) && $multiplier == 1) {
                        $fallback_image_style = $styleTransformer->getName();
                    }
                }
            }
        }
        if (!$fallback_image_style) {
            $fallback_image_style = $automatic_fallback_image_style;
        }
        $this->setFallbackImageStyle($fallback_image_style);
        $this->addImageStyleMappings($image_style_mappings);

    }

    protected function setFallbackImageStyle($fallback_image_style)
    {
        $config = $this->configAccumulator->getConfig($this->getConfigName());
        $config['fallback_image_style'] = $fallback_image_style;
        $this->configAccumulator->setConfig($this->getConfigName(), $config);
    }

    protected function addImageStyleMappings($image_style_mappings)
    {
        $config = $this->configAccumulator->getConfig($this->getConfigName());
        $config['image_style_mappings'] = $image_style_mappings;
        $this->configAccumulator->setConfig($this->getConfigName(), $config);
    }

    public function getTemplateFileName()
    {
        return 'image/responsive_image.styles.template.yml';
    }

    public function getConfigName()
    {
        return 'responsive_image.styles.'.$this->data['id'];
    }

    public function getTemplateOverrideData()
    {
        $data = parent::getTemplateOverrideData();
        $data['breakpoint_group'] = $this->data['breakpoint_group'];
        return $data;
    }

    /**
     * Generate style identifier.
     */
    protected function getStyleIdentifierOccCropStyle($id, $breakpoint, $multiplier) {
        $id = str_replace('x', '_', $id);
        return implode('__', [
            $id,
            $breakpoint,
            $multiplier
        ]);
    }

    /**
     * Generate style identifier.
     */
    protected function getStyleIdentifierOccFpStyle($id, $breakpoint, $multiplier) {
        $id = str_replace('x', '_', $id);
        return implode('__', [
            $id,
            $breakpoint,
            $multiplier
        ]);
    }
}
