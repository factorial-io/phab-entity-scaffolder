<?php


namespace Phabalicious\Scaffolder\Transformers\Utils\Plugins;

use Phabalicious\Scaffolder\Transformers\Utils\FieldField;
use Phabalicious\Utilities\Utilities;

class CtaFieldField extends FieldField
{
    protected static $mapping = [
        'EXTERNAL_AND_INTERNAL' => 17,
        'EXTERNAL_ONLY' => 16,
        'INTERNAL_ONLY' => 15,
    ];

    protected function getTemplateOverrideData()
    {
        return Utilities::mergeData(
            parent::getTemplateOverrideData(),
            [
                'settings' => [
                    'title' => $this->data['title'] ?? 1,
                    'link_type' => $this->lookupLinkType($this->data['link_type'] ?? 'EXTERNAL_AND_INTERNAL')
                ],
            ]
        );
    }

    private function lookupLinkType($link_type)
    {
        $value = self::$mapping[strtoupper($link_type)] ?? null;
        if (is_null($value)) {
            throw new \RuntimeException('Unknown link_type for cta field: ' . $link_type);
        }
        return $value;
    }
}
