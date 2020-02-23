<?php

namespace Phabalicious\Scaffolder\Transformers;

class EntityTransformerBase extends EntityScaffolderTransformerBase
{
    protected function getTemplateOverrideData($data) {
        return [
            'uuid' => $this::PRESERVE_IF_AVAILABLE,
            'id' => $data['id'],
            'label' => $data['label'],
        ];
    }
}
