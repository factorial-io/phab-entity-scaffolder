<?php

namespace Phabalicious\Scaffolder\Transformers;

use Phabalicious\Scaffolder\Transformers\Utils\MediaEntity;

class MediaTypeTransformer extends EntityTypeTransformer implements DataTransformerInterface
{
    public static function getName() : string
    {
        return 'media';
    }

    public static function requires() : string
    {
        return "3.4";
    }

    public function getEntityTypeClassName()
    {
        return MediaEntity::class;
    }
}
