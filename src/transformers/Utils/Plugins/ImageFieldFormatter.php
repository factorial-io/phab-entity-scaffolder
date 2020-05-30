<?php


namespace Phabalicious\Scaffolder\Transformers\Utils\Plugins;

use Phabalicious\Scaffolder\Transformers\Utils\FieldFormatter;

class ImageFieldFormatter extends FieldFormatter
{

    protected function getTemplateOverrideData()
    {
        $out = parent::getTemplateOverrideData();
        if (!empty($this->data['responsive_image_style'])) {
            $out['settings']['responsive_image_style'] = $this->data['responsive_image_style'];
            $out['type'] = 'responsive_image';
        } elseif (!empty($this->data['image_style'])) {
            $out['settings']['image_style'] = $this->data['image_style'];
            $out['type'] = 'image';
        }

        return $out;
    }
}
