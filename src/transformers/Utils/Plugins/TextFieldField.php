<?php


namespace Phabalicious\Scaffolder\Transformers\Utils\Plugins;

use Phabalicious\Scaffolder\Transformers\Utils\FieldField;
use Phabalicious\Utilities\Utilities;

class TextFieldField extends FieldField
{

    protected function getTemplateOverrideData()
    {
        $allowed_formats = $this->data['allowed_formats'] ?? [];
        return Utilities::mergeData(
            parent::getTemplateOverrideData(),
            [
                'third_party_settings' => [
                    'allowed_formats' => array_combine($allowed_formats, $allowed_formats),
                ],
            ]
        );
    }
}
