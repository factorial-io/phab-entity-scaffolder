<?php

namespace Phabalicious\Scaffolder\Transformers\Utils\Plugins;

use Phabalicious\Scaffolder\Transformers\Utils\FieldField;

class ListPredefinedOptionsFieldField extends FieldField
{
    protected function getTemplateOverrideData()
    {
        $out = parent::getTemplateOverrideData();
        
        if (isset($this->data['default_value'])) {
            $default_value = $this->data['default_value'];
            $default_value = is_array($default_value) ? $default_value : [ $default_value ];
            $out['default_value'] = array_map(function ($elem) {
                return [ "value" => $elem ];
            }, $default_value);
        }
        return $out;
    }
}
