<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

abstract class Base implements BaseInterface
{

    protected $template = [];

    /**
     * @var \Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService
     */
    protected $placeholderService;

    /**
     * @var \Phabalicious\Scaffolder\Transformers\Utils\ConfigAccumulator
     */
    protected $configAccumulator;

    protected $data = [];

    /**
     * Base constructor.
     *
     * @param \Phabalicious\Scaffolder\Transformers\Utils\ConfigAccumulator $config_accumulator
     * @param \Phabalicious\Scaffolder\Transformers\Utils\PlaceholderService $placeholder_service
     * @param array $data
     *
     * @throws \Phabalicious\Scaffolder\Transformers\Utils\UnknownScaffoldTypeException
     */
    public function __construct(
        ConfigAccumulator $config_accumulator,
        PlaceholderService $placeholder_service,
        array $data
    ) {
        $this->template = PlaceholderService::parseTemplateFile($this->getTemplateFile());
        $this->configAccumulator = $config_accumulator;
        $this->placeholderService = $placeholder_service;
        $this->data = $data;
    }

    /**
     * Get template file.
     */
    protected function getTemplateFile(): string
    {
        return $this->getTemplateDir() . '/' . $this->getTemplateFileName();
    }


    /**
     * Get template dir.
     */
    protected function getTemplateDir(): string
    {
        return __DIR__ . '/templates';
    }

    /**
     * Get configurations.
     */
    public function getConfigurations(): array
    {
        return $this->configAccumulator->get();
    }

    public function getDependencies(): array
    {
        return [];
    }

    protected function getTemplateOverrideData(): array
    {
        // @TODO Fill $data with existing template data.
        $data = $this->data;
        $out = [];
        $mandatory_key_names = [
            'id' => 'id',
            'label' => 'label',
        ];
        foreach ($mandatory_key_names as $key => $target) {
            $out[$key] = $data[$target];
        }
        $optional_keys_map = [
        'description' => 'description',
        ];
        foreach ($optional_keys_map as $key => $target) {
            if (isset($data[$target])) {
                $out[$key] = $data[$target];
            }
        }
        $out['uuid'] = PlaceholderService::REUSE_OR_CREATE_VALUE;

        // @TODO Find a way to preserve the type of the data
        // after merge.
        // For example boolean false becomes empty during export.
        return $out;
    }
}
