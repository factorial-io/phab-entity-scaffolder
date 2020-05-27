<?php


namespace Phabalicious\Scaffolder\Transformers\Utils\Plugins;

use Phabalicious\Scaffolder\Transformers\Utils\FieldField;
use Phabalicious\Utilities\Utilities;

class FileFieldField extends FieldField
{

    protected function getTemplateOverrideData()
    {
        return Utilities::mergeData(
            parent::getTemplateOverrideData(),
            [
                'settings' => [
                    'file_directory' => $this->data['file_directory'] ?? null,
                    'file_extensions' => $this->data['file_extensions'] ?? null,
                    'description_field' => $this->data['has_description_field'] ?? null,
                ],
            ]
        );
    }
}
