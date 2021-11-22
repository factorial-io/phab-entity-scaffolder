<?php

namespace Phabalicious\Scaffolder\Transformers;

use Phabalicious\Scaffolder\Transformers\Utils\BlockContentEntity;

class BlockContentTypeTransformer extends EntityTypeTransformer implements DataTransformerInterface
{
    public static function getName() : string
    {
        return 'block_content';
    }

    public static function requires() : string
    {
        return "3.4";
    }

    public function getEntityTypeClassName()
    {
        return BlockContentEntity::class;
    }
}
