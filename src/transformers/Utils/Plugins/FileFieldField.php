<?php


namespace Phabalicious\Scaffolder\Transformers\Utils\Plugins;

use Phabalicious\Exception\ValidationFailedException;
use Phabalicious\Scaffolder\Transformers\Utils\FieldField;
use Phabalicious\Utilities\Utilities;
use Phabalicious\Validation\ValidationErrorBag;
use Phabalicious\Validation\ValidationService;

class FileFieldField extends FieldField
{
    public function __construct($entity_type, $data, $parent)
    {
        parent::__construct($entity_type, $data, $parent);
        $service = new ValidationService($data, new ValidationErrorBag(), "file");
        $service->hasKey('file_extensions', 'A file field needs a list of allowed extensions, separated by space');
        if ($service->getErrorBag()->hasErrors()) {
            throw new ValidationFailedException($service->getErrorBag());
        }
    }

    protected function getTemplateOverrideData()
    {
        return Utilities::mergeData(
            parent::getTemplateOverrideData(),
            [
                'settings' => [
                    'file_directory' => $this->data['file_directory'] ?? '[date:custom:Y]-[date:custom:m]',
                    'file_extensions' => $this->data['file_extensions'],
                    'description_field' => $this->data['has_description_field'] ?? false,
                ],
            ]
        );
    }
}
