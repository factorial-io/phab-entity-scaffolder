<?php


namespace Phabalicious\Scaffolder\Transformers\Utils\Plugins;

use Phabalicious\Scaffolder\Transformers\Utils\FieldFormatter;
use Phabalicious\Utilities\Utilities;

class CtaFieldFormatter extends FieldFormatter
{

    protected function getTemplateOverrideData(): array
    {
        return Utilities::mergeData(
            parent::getTemplateOverrideData(),
            [
                'settings' => [
                    'rel' => $this->getDataValue('rel', ''),
                    'target' => $this->getDataValue('target', ''),
                ],
            ]
        );
    }
}
