<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Exception\ValidationFailedException;
use Phabalicious\Utilities\Utilities;
use Phabalicious\Validation\ValidationErrorBag;
use Phabalicious\Validation\ValidationService;

class MediaEntity extends EntityBase
{

    public function __construct(ConfigAccumulator $config_accumulator, PlaceholderService $placeholder_service, $data)
    {

        $errors = new ValidationErrorBag();
        $validation = new ValidationService($data, $errors, "media");
        $validation->hasKey('source', 'A media entity needs a source');
        $validation->hasKey('source_field', 'A media entity needs a source_field');

        if (!empty($data['source_field'])) {
            $source_field = $data['source_field'];
            $validation->arrayContainsKey($source_field, $data['fields'], 'source_field not found in list of fields');
        }
        if ($errors->hasErrors()) {
            throw new ValidationFailedException($errors);
        }
        
        parent::__construct($config_accumulator, $placeholder_service, $data);

        // Merge again with override data, as we need the already scaffolded fields.
        $config = Utilities::mergeData(
            $this->configAccumulator->getConfig($this->getConfigName()),
            $this->getOverrideData()
        );
        $this->configAccumulator->setConfig($this->getConfigName(), $config);
    }

    public function getEntityType()
    {
        return 'media';
    }

    public function getDependencies()
    {
        return [
            'module' => [
                'media'
            ],
        ];
    }
    
    public function getOverrideData()
    {
        $out = [];
        $out['source'] = $this->data['source'];
        $out['source_configuration']['source_field'] = $this->fields[$this->data['source_field']];
        
        return $out;
    }
}
