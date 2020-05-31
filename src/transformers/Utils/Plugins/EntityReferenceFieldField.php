<?php


namespace Phabalicious\Scaffolder\Transformers\Utils\Plugins;

use Phabalicious\Exception\ValidationFailedException;
use Phabalicious\Scaffolder\Transformers\Utils\FieldField;
use Phabalicious\Utilities\Utilities;
use Phabalicious\Validation\ValidationErrorBag;
use Phabalicious\Validation\ValidationService;

class EntityReferenceFieldField extends FieldField
{

    public function __construct($entity_type, $data, $parent)
    {
        parent::__construct($entity_type, $data, $parent);
        $service = new ValidationService($data, new ValidationErrorBag(), "entity_reference");
        $service->hasKey('bundles', 'An entity reference field requires a list of allowed bundles.');
        if ($service->getErrorBag()->hasErrors()) {
            throw new ValidationFailedException($service->getErrorBag());
        }
    }

    protected function getTemplateOverrideData()
    {
        $data = parent::getTemplateOverrideData();

        $bundles = [];
        if (isset($this->data['bundles']) && !empty($this->data['bundles']) && is_array($this->data['bundles'])) {
            foreach ($this->data['bundles'] as $bundle) {
                $bundles[$bundle] = $bundle;
            }
        }
        switch ($this->getFieldSubType()) {
            case 'node':
                if ($bundles) {
                    $data['settings']['handler_settings']['target_bundles'] = $bundles;
                    foreach ($bundles as $bundle) {
                        $data['dependencies']['config'][] = 'node.type.'.$bundle;
                    }
                }
                break;

            case 'taxonomy_term':
                if ($bundles) {
                    $data['settings']['handler_settings']['target_bundles'] = $bundles;
                    foreach ($bundles as $bundle) {
                        $data['dependencies']['config'][] = 'taxonomy.vocabulary.'.$bundle;
                    }
                }
                break;

            case 'media':
                if ($bundles) {
                    $data['settings']['handler_settings']['target_bundles'] = $bundles;
                    $data['settings']['handler_settings']['sort']['field'] = $this->getFieldName().'.title';
                    foreach ($bundles as $bundle) {
                        $data['dependencies']['config'][] = 'media.type.'.$bundle;
                    }
                }
                break;
                
            case 'paragraphs':
                if ($bundles) {
                    $data['settings']['handler_settings']['target_bundles'] = $bundles;
                    $dnd = [];
                    foreach ($bundles as $ndx => $bundle) {
                        $dnd[$bundle] = [
                            "enabled" => true,
                            "weight" => $ndx,
                        ];
                    }
                    $data['settings']['handler_settings']['target_bundles_drag_drop'] = $dnd;
                    foreach ($bundles as $bundle) {
                        $data['dependencies']['config'][] = 'paragraphs.paragraphs_type.'.$bundle;
                    }
                }
                break;
        }
        return $data;
    }
}
