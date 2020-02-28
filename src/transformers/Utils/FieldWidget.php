<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Utilities\Utilities;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;
use Phabalicious\Scaffolder\Transformers\Utils\FieldTransformerBase;


class FieldWidget extends FieldTransformerBase
{

    protected function getTemplateFileName()
    {
        return 'field/' . $this->data['type']. '/form.yml';
    }

    protected function getTemplateOverrideData()
    {
        return [
            'weight' => $this->data['weight'],
        ];
    }

    public function getViewModeSpecificConfig($view_mode = 'default') {
        return $this->getConfig()['content'][$view_mode];
    }

}
