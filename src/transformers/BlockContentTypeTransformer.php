<?php

namespace Phabalicious\Scaffolder\Transformers;

class BlockContentTypeTransformer extends EntityScaffolderTransformerBase
{
    public static function getName()
    {
        return 'block_content.type';
    }

    protected function getTemplateOverrideData($data) {
        return [
            'uuid' => $this::PRESERVE_IF_AVAILABLE,
            'id' => $data['id'],
            'label' => $data['label'],
        ];
    }
}
