<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

interface ConfigAccumulatorConsumableInterface
{

    /**
     * Get the name of the config.
     */
    public function getConfigName(): string;

    /**
     * Get the config values.
     *
     * @return mixed
     */
    public function getConfig();
}
