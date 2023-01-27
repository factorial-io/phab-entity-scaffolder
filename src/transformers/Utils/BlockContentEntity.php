<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

class BlockContentEntity extends EntityBase
{

    public function getEntityType(): string
    {
        return 'block_content';
    }

    public function getDependencies(): array
    {
        return [
            'module' => [
                'block_content'
            ],
        ];
    }
}
