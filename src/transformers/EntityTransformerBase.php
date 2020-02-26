<?php

namespace Phabalicious\Scaffolder\Transformers;

require_once __DIR__ . '/Utils/ConfigService.php';
require_once __DIR__ . '/Utils/PlaceholderService.php';
require_once __DIR__ . '/Utils/BlockContentEntity.php';

use Phabalicious\Scaffolder\Transformers\Utils\BlockContentEntity;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;
use Phabalicious\Scaffolder\Transformers\Utils\ConfigService;
use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Utilities\Utilities;

abstract class EntityTransformerBase extends YamlTransformer implements DataTransformerInterface
{

    public static function getName()
    {
        return '???';
    }

    public static function requires()
    {
        return "3.4";
    }

    public function transform(TaskContextInterface $context, array $files): array
    {
        $results = [];
        foreach ($this->iterateOverFiles($context, $files) as $data) {
            $config_service = new ConfigService();
            $placeholder_service = new PlaceholderService();
            $transformer = $this->getTransformer($config_service, $placeholder_service, $data);
            $results += $transformer->getConfigurations();
        }
        $placeholder_service = new PlaceholderService();
        $placeholder_service->postTransform($results);
        return $this->asYamlFiles($results);
    }

    public function getTransformer($config_service, $placeholder_service, $data) {
        return new BlockContentEntity($config_service, $placeholder_service, $data);
    }

}
