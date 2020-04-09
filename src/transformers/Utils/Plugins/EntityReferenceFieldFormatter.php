<?php


namespace Phabalicious\Scaffolder\Transformers\Utils\Plugins;

use Phabalicious\Scaffolder\Transformers\Utils\FieldFormatter;

class EntityReferenceFieldFormatter extends FieldFormatter
{

    protected function getTemplateOverrideData()
    {
        $out = parent::getTemplateOverrideData();
        $out['settings']['view_mode'] = $this->data['view_mode'] ?? 'default';

        return $out;
    }
}
