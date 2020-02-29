<?php

namespace Phabalicious\Scaffolder\Transformers;

require_once __DIR__ . '/Utils/ConfigService.php';
require_once __DIR__ . '/Utils/PlaceholderService.php';
require_once __DIR__ . '/Utils/ImageStyle.php';
require_once __DIR__ . '/Utils/ImageEffect.php';

use Phabalicious\Scaffolder\Transformers\Utils\ImageStyle;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;
use Phabalicious\Scaffolder\Transformers\Utils\ConfigService;
use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Utilities\Utilities;

class ResponsiveImageTransformer extends YamlTransformer implements DataTransformerInterface
{
    public static function getName()
    {
        return 'responsive_image';
    }

    public static function requires()
    {
        return "3.4";
    }

    public function transform(TaskContextInterface $context, array $files): array
    {
        $results = [];
        $placeholder_service = new PlaceholderService();
        foreach ($this->iterateOverFiles($context, $files) as $data) {
            $multipliers = [1];
            if (!empty($data['multipliers'])) {
              $multipliers = $data['multipliers'];
            }
            foreach ($multipliers as $multiplier) {
                if (is_array($data['mapping'])) {
                    foreach($data['mapping'] as $responsive_group => $style_data) {
                        $style_data['multiplier'] = $multiplier;
                        $config_service = new ConfigService();
                        $transformer = new ImageStyle($config_service, $placeholder_service, $style_data);
                        $results += $transformer->getConfigurations();
                    }
                }
            }

        }
        $placeholder_service->postTransform($results);
        return $this->asYamlFiles($results);
    }

}
