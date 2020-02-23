<?php

namespace Phabalicious\Scaffolder\Transformers;

class BlockContentTypeTransformer extends EntityTransformerBase
{
    const ENTITY_NAME = 'block_content';

    public static function getName()
    {
        return self::ENTITY_NAME;
    }

    public function getConfigName($id) 
    {
        return $this::ENTITY_NAME . '.type.' . $id;
    }

}
