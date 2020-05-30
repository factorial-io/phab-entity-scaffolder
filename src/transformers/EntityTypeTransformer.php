<?php

namespace Phabalicious\Scaffolder\Transformers;

use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;
use Phabalicious\Scaffolder\Transformers\Utils\ConfigAccumulator;
use Phabalicious\Method\TaskContextInterface;

abstract class EntityTypeTransformer extends YamlTransformer implements EntityTypeTransformerInterface
{
    
    public function transform(TaskContextInterface $context, array $files, $target_path): array
    {
        $results = [];
        $placeholder_service = new PlaceholderService();
        $class = $this->getEntityTypeClassName();
        foreach ($this->iterateOverFiles($context, $files) as $data) {
            $config_accumulator = new ConfigAccumulator();
            $transformer = new $class($config_accumulator, $placeholder_service, $data);
            $results += $transformer->getConfigurations();
        }
        $results = $placeholder_service->postTransform($results, $target_path);
        return $this->asYamlFiles($results);
    }
}
