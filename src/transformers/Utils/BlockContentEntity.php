<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Scaffolder\Transformers\Utils\EntityBase;


class BlockContentEntity extends EntityBase
{
    const ENTITY_TYPE = 'block_content';

    public function getDependencies() {
        return [
            'module' => [
                'block_content'
            ],
        ];
    }
}
