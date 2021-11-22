<?php

namespace Phabalicious\Scaffolder\Transformers;

use Phabalicious\Scaffolder\Transformers\Utils\ResponsiveImage;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;
use Phabalicious\Scaffolder\Transformers\Utils\ConfigAccumulator;
use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Utilities\Utilities;

class ResponsiveImageTransformer extends YamlTransformer implements DataTransformerInterface
{
    public static function getName() : string
    {
        return 'responsive_image';
    }

    public static function requires() : string
    {
        return "3.4";
    }

    public function transform(TaskContextInterface $context, array $files, $target_path): array
    {
        $results = [];
        $placeholder_service = new PlaceholderService();
        foreach ($this->iterateOverFiles($context, $files) as $data) {
            $config_accumulator = new ConfigAccumulator();
            $transformer = new ResponsiveImage($config_accumulator, $placeholder_service, $data);
            $results += $transformer->getConfigurations();
        }
        $results = $placeholder_service->postTransform($results, $target_path);
        return $this->asYamlFiles($results);
    }
}
