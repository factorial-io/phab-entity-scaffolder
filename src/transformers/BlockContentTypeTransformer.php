<?php

namespace Phabalicious\Scaffolder\Transformers;

use Phabalicious\Scaffolder\Transformers\Utils\BlockContentEntity;

class BlockContentTypeTransformer extends EntityTypeTransformer implements DataTransformerInterface
{
    public static function getName()
    {
        return 'block_content';
    }

    public static function requires()
    {
        return "3.4";
    }

    public function getEntityTypeClassName()
    {
        return BlockContentEntity::class;
    }
}
