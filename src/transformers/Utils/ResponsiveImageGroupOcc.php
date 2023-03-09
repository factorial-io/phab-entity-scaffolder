<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Scaffolder\Transformers\Utils\Base;
use Phabalicious\Scaffolder\Transformers\Utils\ImageEffect;
use Phabalicious\Utilities\Utilities;

class ResponsiveImageGroupOcc extends Base
{

    public function __construct(ConfigAccumulator $config_accumulator, PlaceholderService $placeholder_service, $data)
    {
        parent::__construct($config_accumulator, $placeholder_service, $data);
        $this->transformResponsiveImageGroup();
    }

    protected function transformResponsiveImageGroup()
    {
        $fallback_image_style = $automatic_fallback_image_style = false;
        $data = $this->data;
        $crops = $data['crops'];
        $id = $data['id'];
        foreach ($crops as $crop) {
            $group_data = $data;
            $group_data['id'] = $id . '__' . $crop;
            $group_data['crop'] = $crop;
            $group_data['type'] = 'manual_crop';
            $styleTransformer = new ResponsiveImageOcc(
                $this->configAccumulator,
                $this->placeholderService,
                $group_data
            );
        }
        foreach ($crops as $crop) {
            $group_data = $data;
            $group_data['id'] = $id . '__' . $crop . '_fp';
            $group_data['crop'] = $crop;
            $group_data['type'] = 'focal_point';
            $styleTransformer = new ResponsiveImageOcc(
                $this->configAccumulator,
                $this->placeholderService,
                $group_data
            );
        }

    }
    public function getTemplateFileName()
    {
        return 'image/responsive_image.styles.template.yml';
    }

}
