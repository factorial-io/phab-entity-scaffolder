<?php

namespace Phabalicious\Scaffolder\Transformers;

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Utilities\Utilities;

abstract class EntityScaffolderTransformerBase extends YamlTransformer implements DataTransformerInterface
{

    protected $template = [];

    const PRESERVE_IF_AVAILABLE = '__PRESERVE__';

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
        $this->template = \Symfony\Component\Yaml\Yaml::parseFile($this->getTemplate());
    }

    protected function getTemplate() 
    {
        return $this->getTemplateDir() . '/' . $this->getTemplateFileName('template');
    }

    protected function getTemplateFileName($id) 
    {
        return $this->getName() . '.' . $id . '.yml';
    }

    protected function getTemplateDir() 
    {
        return __DIR__ . '/templates';
    }

    protected function postTransform($results, $existing = []) 
    {
        foreach ($results as $key => &$result) {
            if ($result == $this::PRESERVE_IF_AVAILABLE) {
                $result = '';
                if (isset($existing[$key])) {
                    $result = $existing[$key];
                }
                else {
                    // UUID is special, 
                    // since we can't have it empty.
                    if ($key == 'uuid') {
                        $result = Utilities::generateUUID();
                    }
                }
            }            
        }
    }

    public function transform(TaskContextInterface $context, array $files): array
    {
        $results = [];
        foreach ($this->iterateOverFiles($context, $files) as $data) {
            $result = Utilities::mergeData($this->template, $this->getTemplateOverrideData($data));
            $results[$this->getTemplateFileName($data['id'])] = $result;
        }
        $this->postTransform($results);
        return $this->asYamlFiles($results);
    }

    protected function getTemplateOverrideData($data) {
        return [
            'uuid' => $this::PRESERVE_IF_AVAILABLE,
        ];
    }
}
