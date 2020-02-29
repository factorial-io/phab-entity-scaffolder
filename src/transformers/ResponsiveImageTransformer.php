<?php

namespace Phabalicious\Scaffolder\Transformers;

require_once __DIR__ . '/Utils/ConfigService.php';
require_once __DIR__ . '/Utils/PlaceholderService.php';
require_once __DIR__ . '/Utils/ImageStyle.php';
require_once __DIR__ . '/Utils/ImageEffect.php';
require_once __DIR__ . '/Utils/ResponsiveImage.php';

use Phabalicious\Scaffolder\Transformers\Utils\ResponsiveImage;
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
            $config_service = new ConfigService();
            $transformer = new ResponsiveImage($config_service, $placeholder_service, $data);
            $results += $transformer->getConfigurations();
        }
        $placeholder_service->postTransform($results);
        return $this->asYamlFiles($results);
    }

}
