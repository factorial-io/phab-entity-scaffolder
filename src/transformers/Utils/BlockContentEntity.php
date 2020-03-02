<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Scaffolder\Transformers\Utils\EntityBase;


class BlockContentEntity extends EntityBase
{

    public function getEntityType() {
        return 'block_content';
    }

    public function getDependencies() {
        return [
            'module' => [
                'block_content'
            ],
        ];
    }
}
