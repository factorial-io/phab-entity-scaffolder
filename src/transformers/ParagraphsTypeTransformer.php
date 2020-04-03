<?php

namespace Phabalicious\Scaffolder\Transformers;

use Phabalicious\Scaffolder\Transformers\Utils\ParagraphEntity;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;
use Phabalicious\Scaffolder\Transformers\Utils\ConfigAccumulator;
use Phabalicious\Method\TaskContextInterface;

class ParagraphsTypeTransformer extends YamlTransformer implements DataTransformerInterface
{
    public static function getName()
    {
        return 'paragraphs';
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
            $transformer = new ParagraphEntity($config_accumulator, $placeholder_service, $data);
            $results += $transformer->getConfigurations();
        }
        $results = $placeholder_service->postTransform($results, $target_path);
        return $this->asYamlFiles($results);
    }
}
