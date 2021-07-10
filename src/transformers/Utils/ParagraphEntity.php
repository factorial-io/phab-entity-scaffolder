<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

class ParagraphEntity extends EntityBase
{

    public function getEntityType(): string
    {
        return 'paragraph';
    }


    public function getConfigName(): string
    {
        return 'paragraphs.paragraphs_type.' . $this->bundle;
    }

    public function getDependencies(): array
    {
        return [];
    }
}
