<?php

namespace Phabalicious\Scaffolder\Transformers;

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;
use Phabalicious\Utilities\Utilities;
use Symfony\Component\Yaml\Yaml;

class ImageStyleTransformer extends YamlTransformer implements DataTransformerInterface
{

    protected $template = [];

    public static function getName()
    {
        return 'imagestyles';
    }

    public static function requires()
    {
        return "3.4";
    }

    public function __construct()
    {
        $this->template = PlaceholderService::parseTemplateFile(__DIR__ . '/Utils/templates/image.style.template.yml');
    }

    public function transform(
        TaskContextInterface $context,
        array $files,
        $target_path
    ): array {
        $placeholder_service = new PlaceholderService();
        $results = [];

        foreach ($this->iterateOverFiles($context, $files) as $data) {
            $multipliers = $data['multiplier'] ?? ['1x'];
            $image_styles = $data['image_styles'] ?? [];
            foreach ($multipliers as $multiplier) {
                foreach ($image_styles as $style) {
                    $result = Utilities::mergeData($this->template, [
                      'uuid' => PlaceholderService::REUSE_OR_CREATE_VALUE,
                      'name' => $data['prefix']['machine_name'] . '_' . $style['machine_name'] . '__' . $multiplier,
                      'label' => $data['prefix']['name'] . ' ' . $style['machine_name'] . ' @ ' . $multiplier,
                      'effects' => [],
                    ]);

                    foreach ($style['effects'] as $weight => $effect) {
                        $result_effect = $effect;
                        $result_effect['uuid'] = PlaceholderService::REUSE_OR_CREATE_VALUE;
                        $result_effect['id'] = $effect['name'];
                        $result_effect['weight'] = $effect['weight'] ?? $weight;

                        unset($result_effect['name']);

                        if ($result_effect['id'] == 'focal_point_scale_and_crop') {
                            $result['dependencies']['module'][] = 'focal_point';
                            $result_effect['data']['crop_type'] = 'focal_point';
                        }

                        foreach (['width', 'height'] as $key) {
                            if (isset($result_effect['data'][$key])) {
                                $result_effect['data'][$key] *= intval(
                                    $multiplier,
                                    10
                                );
                            }
                        }

                        $result['effects'][PlaceholderService::createChildReference('uuid')] = $result_effect;
                    }

                    $results['image.style.' . $result['name'] . '.yml'] = $result;
                }
            }
        }

        $results = $placeholder_service->postTransform($results, $target_path);

        return $this->asYamlFiles($results);
    }
}
