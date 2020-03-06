<?php

namespace Phabalicious\Scaffolder\Transformers;

use Phabalicious\Scaffolder\Transformers\Utils\BlockContentEntity;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;
use Phabalicious\Scaffolder\Transformers\Utils\ConfigAccumulator;
use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Utilities\Utilities;

class BlockContentTypeTransformer extends YamlTransformer implements DataTransformerInterface
{
    public static function getName()
    {
        return 'block_content';
    }

    public static function requires()
    {
        return "3.4";
    }

    public function transform(TaskContextInterface $context, array $files, $target_path): array
    {
        $results = [];
        $placeholder_service = new PlaceholderService();
        foreach ($this->iterateOverFiles($context, $files) as $data) {
            $config_accumulator = new ConfigAccumulator();
            $transformer = new BlockContentEntity($config_accumulator, $placeholder_service, $data);
            $results += $transformer->getConfigurations();
        }
        $placeholder_service->postTransform($results);
        return $this->asYamlFiles($results);
    }

}
