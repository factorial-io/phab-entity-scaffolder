<?php

namespace Phabalicious\Scaffolder\Transformers;

use Phabalicious\Scaffolder\Transformers\Utils\ParagraphEntity;

class ParagraphsTypeTransformer extends EntityTypeTransformer implements DataTransformerInterface
{
    public static function getName()
    {
        return 'paragraph';
    }

    public static function requires()
    {
        return "3.4";
    }

    public function getEntityTypeClassName()
    {
        return ParagraphEntity::class;
    }
}
