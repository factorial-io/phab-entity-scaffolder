<?php


namespace Phabalicious\Scaffolder\Transformers\Utils\Plugins;

use Phabalicious\Scaffolder\Transformers\Utils\FieldField;
use Phabalicious\Utilities\Utilities;

class EntityReferenceFieldField extends FieldField
{

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
        }
        return $data;
    }
}
