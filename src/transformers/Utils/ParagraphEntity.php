<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Scaffolder\Transformers\Utils\EntityBase;

class ParagraphEntity extends EntityBase
{

    public function getEntityType()
    {
        return 'paragraphs';
    }


    public function getConfigName()
    {
      return $this->getEntityType() . '.paragraphs_type.' . $this->bundle;
    }

    public function getDependencies()
    {
        return [];
    }
}
