<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Scaffolder\Transformers\Utils\EntityBase;

class ParagraphEntity extends EntityBase
{

    public function getEntityType()
    {
        return 'paragraph';
    }


    public function getConfigName()
    {
        return 'paragraphs.paragraphs_type.' . $this->bundle;
    }

    public function getDependencies()
    {
        return [];
    }
}
