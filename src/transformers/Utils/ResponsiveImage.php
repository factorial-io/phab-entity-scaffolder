<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Scaffolder\Transformers\Utils\EsBase;
use Phabalicious\Scaffolder\Transformers\Utils\ImageEffect;
use Phabalicious\Utilities\Utilities;

class ResponsiveImage extends EsBase {

  public function __construct(ConfigService $config_service, PlaceholderService $placeholder_service, $data)
  {
    parent::__construct($config_service, $placeholder_service, $data);
    $config = Utilities::mergeData($this->template, $this->getTemplateOverrideData());
    $this->configService->setConfig($this->getConfigName(), $config);
    $this->transformImageStyles();
  }

  protected function transformImageStyles() {
    $data = $this->data;
    $styleTransformers = [];
    $multipliers = [1];
    if (!empty($data['multipliers'])) {
      $multipliers = $data['multipliers'];
    }
    foreach ($multipliers as $multiplier) {
      if (is_array($data['mapping'])) {
        foreach($data['mapping'] as $breakpoint => $style_data) {
          $style_data['multiplier'] = $multiplier;
          $styleTransformer = new ImageStyle($this->configService, $this->placeholderService, $style_data);
          $styleTransformers[$multiplier][$breakpoint] = $styleTransformer;
        }
      }
    }
  }

  public function getTemplateFileName() {
    return 'image/responsive_image.styles.template.yml';
  }

  public function getConfigName()
  {
    return 'responsive_image.styles.' . $this->data['id'];
  }

}
