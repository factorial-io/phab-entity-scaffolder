<?php

namespace Phabalicious\Scaffolder\Transformers;

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Utilities\Utilities;
use Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService;

abstract class EntityScaffolderTransformerBase extends YamlTransformer implements DataTransformerInterface
{

    protected $template = [];

    protected $placeholderService;

    public static function getName()
    {
        throw new \Exception("Method getName() not implemented.");
    }

    public static function requires()
    {
        return "3.4";
    }

    public function __construct()
    {
        $this->template = \Symfony\Component\Yaml\Yaml::parseFile($this->getTemplateFile());
        $this->placeholderService = new PlaceholderService();
    }

    protected function getTemplateFile() 
    {
        return $this->getTemplateDir() . '/' . $this->getTemplateFileName();
    }

    protected function getTemplateDir() 
    {
        return __DIR__ . '/templates';
    }

    protected function getTemplateFileName() 
    {
        return $this->getConfigName('template') . '.yml';
    }

    /**
     * Get the Drupal config name.
     */
    public function getConfigName($id = '')
    {
        return $this->getName() . '.' . $id;
    }

    public function transform(TaskContextInterface $context, array $files): array
    {
        $results = [];
        foreach ($this->iterateOverFiles($context, $files) as $data) {
            $result = Utilities::mergeData($this->template, $this->getTemplateOverrideData($data));
            $results[$this->getTemplateFileName($data['id'])] = $result;
        }
        $this->placeholderService->postTransform($results);
        return $this->asYamlFiles($results);
    }

    protected function getTemplateOverrideData($data = []) 
    {
        return [
            'uuid' => PlaceholderService::PRESERVE_IF_AVAILABLE,
        ];
    }
}
