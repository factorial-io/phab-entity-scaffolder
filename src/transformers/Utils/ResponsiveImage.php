<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Scaffolder\Transformers\Utils\Base;
use Phabalicious\Scaffolder\Transformers\Utils\ImageEffect;
use Phabalicious\Utilities\Utilities;

class ResponsiveImage extends Base {

  public function __construct(ConfigAccumulator $config_service, PlaceholderService $placeholder_service, $data)
  {
    parent::__construct($config_service, $placeholder_service, $data);
    $config = Utilities::mergeData($this->template, $this->getTemplateOverrideData());
    $this->configAccumulator->setConfig($this->getConfigName(), $config);
    $this->transformImageStyles();
  }

  protected function transformImageStyles() {
    $data = $this->data;
    $image_style_mappings = [];
    $styleTransformers = [];
    $multipliers = ['1x'];
    if (!empty($data['multipliers'])) {
      $multipliers = $data['multipliers'];
    }
    foreach ($multipliers as $multiplier) {
      if (is_array($data['mapping'])) {
        foreach($data['mapping'] as $breakpoint => $style_data) {
          $style_data['multiplier'] = $multiplier;
          $styleTransformer = new ImageStyle($this->configAccumulator, $this->placeholderService, $style_data);
          $styleTransformers[$multiplier][$breakpoint] = $styleTransformer;
          $image_style_mappings[] = [
            'breakpoint_id' => $this->data['breakpoint_group'] . '.' . $breakpoint,
            'multiplier' => $multiplier,
            'image_mapping_type' => 'image_style',
            'image_mapping' => $styleTransformer->getName(),
          ];
        }
      }
    }
    $this->addImageStyleMappings($image_style_mappings);
  }
  protected  function addImageStyleMappings($image_style_mappings) {
      $config = $this->configAccumulator->getConfig($this->getConfigName());
      $config['image_style_mappings'] = $image_style_mappings;
      $this->configAccumulator->setConfig($this->getConfigName(), $config);
  }

  public function getTemplateFileName() {
    return 'image/responsive_image.styles.template.yml';
  }

  public function getConfigName()
  {
    return 'responsive_image.styles.' . $this->data['id'];
  }

  public function getTemplateOverrideData()
  {
    $data = parent::getTemplateOverrideData();
    $data['breakpoint_group'] = $this->data['breakpoint_group'];
    return $data;
  }
}
