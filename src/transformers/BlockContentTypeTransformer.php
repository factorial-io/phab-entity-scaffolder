<?php

namespace Phabalicious\Scaffolder\Transformers;

require_once __DIR__ . '/Utils/BlockContentEntity.php';

use Phabalicious\Scaffolder\Transformers\Utils\BlockContentEntity;
use Phabalicious\Scaffolder\Transformers\EntityTransformerBase;


class BlockContentTypeTransformer extends EntityTransformerBase
{
    public static function getName()
    {
        return 'block_content';
    }

    public function getTransformer($config_service, $placeholder_service, $data)
    {
        return new BlockContentEntity($config_service, $placeholder_service, $data);
    }
}
