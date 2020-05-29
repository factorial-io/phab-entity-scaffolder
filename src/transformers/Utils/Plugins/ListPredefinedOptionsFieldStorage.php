<?php

namespace Phabalicious\Scaffolder\Transformers\Utils\Plugins;

use Phabalicious\Scaffolder\Transformers\Utils\FieldStorage;

class ListPredefinedOptionsFieldStorage extends FieldStorage
{

    protected function getTemplateOverrideData()
    {
        $out = parent::getTemplateOverrideData();
        if (empty($this->data['plugin_id'])) {
            throw new \Exception('Select field needs a valid plugin id for `plugin_id`!');
        }
        $plugin_id = $this->data['plugin_id'];
        $out['third_party_settings']['list_predefined_options']['plugin_id'] = $plugin_id;
        return $out;
    }
}
