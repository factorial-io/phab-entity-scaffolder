<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Exception\ValidationFailedException;
use Phabalicious\Validation\ValidationErrorBag;
use Phabalicious\Validation\ValidationService;

class NodeEntity extends EntityBase
{

    public function __construct(ConfigAccumulator $config_accumulator, PlaceholderService $placeholder_service, $data)
    {

        $errors = new ValidationErrorBag();
        $validation = new ValidationService($data, $errors, "node");

        if ($errors->hasErrors()) {
            throw new ValidationFailedException($errors);
        }
        
        parent::__construct($config_accumulator, $placeholder_service, $data);
    }

    public function getEntityType(): string
    {
        return 'node';
    }

    public function getDependencies(): array
    {
        return [
            'module' => [
                'node'
            ],
        ];
    }
    
    public function getTemplateOverrideData(): array
    {
        $out = parent::getTemplateOverrideData();
        $out['help'] = $this->data['help'] ?? '';
        $out['new_revision'] = $this->data['new_revision'] ?? true;
        return $out;
    }
}
