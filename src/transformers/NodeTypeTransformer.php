<?php

namespace Phabalicious\Scaffolder\Transformers;

use Phabalicious\Scaffolder\Transformers\Utils\NodeEntity;

class NodeTypeTransformer extends EntityTypeTransformer implements DataTransformerInterface
{
    public static function getName()
    {
        return 'node';
    }

    public static function requires()
    {
        return "3.4";
    }

    public function getEntityTypeClassName()
    {
        return NodeEntity::class;
    }
}
