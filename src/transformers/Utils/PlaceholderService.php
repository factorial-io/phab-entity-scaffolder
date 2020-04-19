<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Utilities\Utilities;
use Symfony\Component\Yaml\Yaml;

class PlaceholderService
{

    const REUSE_OR_CREATE_VALUE = '__REUSE__';

    const CREATE_NEW_VALUE = '__NEW__';

    protected const CHILD_REFERENCE = '__CHILD_REF__';

    // es.yml > existing configuration
    const STRATEGY_OVERRIDE_FROM_EXISTING = '_$';
    // es.yml > existing configuration > template.yml
    const STRATEGY_INHERIT_FROM_EXISTING = '__';
    // es.yml > template.yml
    const STRATEGY_IGNORE = '';

    protected $placeholders;

    public static function createChildReference(string $key)
    {
        static $counter = 0;
        $counter++;
        return implode('|', [self::CHILD_REFERENCE, $counter, $key]);
    }

    public static function parseTemplateFile($filename)
    {
        return Yaml::parseFile($filename);
    }

    public function postTransform($items, $target_path)
    {
        $return = [];
        foreach ($items as $file => $results) {
            $existing = [];
            try {
                $existing = Yaml::parseFile($target_path . '/' . $file);
            } catch (\Exception $e) {
                ; // Ignore intentionally
            }
            if (!empty($existing)) {
                $results = $this->adjustValuesFromExistingConfig($results, $existing);
            }
            $results = $this->postTransformValues($results, $existing);
            $return[$file] = $results;
            ;
        }
        return $return;
    }

    protected function postTransformValues($values, $existing)
    {
        $results = [];
        foreach ($values as $key => $value) {
            $result = $this->postTransformValue($key, $value, $existing[$key] ?? null);
            $key = $this->dereferenceKey($key, $result);
            $results[$key] = $result;
        }
        return $results;
    }

    protected function postTransformValue($key, $value, $existing)
    {
        if (is_array($value)) {
            return $this->postTransformValues($value, $existing);
        }

        if ($value === self::REUSE_OR_CREATE_VALUE) {
            return is_null($existing) ? $this->createNewValueForKey($key, $existing) : $existing;
        } elseif ($value === self::CREATE_NEW_VALUE) {
            return $this->createNewValueForKey($key, $existing);
        }

        // Fall through, do nothing.
        return $value;
    }

    protected function createNewValueForKey($key, $existing_value)
    {
        if ($key == 'uuid') {
            return Utilities::generateUuid();
        }

        throw new \RuntimeException('Could not create value for key ' . $key);
    }

    protected function dereferenceKey($key, $value)
    {
        list($identifier,,$child_key) = array_pad(explode('|', $key), 3, false);
        if ($identifier === self::CHILD_REFERENCE) {
            if (!isset($value[$child_key])) {
                throw new \RuntimeException('Could not dereference child key ' . $child_key);
            }
            return $value[$child_key];
        }
        return $key;
    }

    /**
     * Decode the placeholder strategy and parameters in the given key.
     *
     * @param $key
     *  Array key.
     *
     * @return array
     *  An array with strategy as first item, followed by parameters.
     */
    private function getPlaceholderStrategyForKey($key)
    {
        $values = explode('|', $key);
        // Probably the case when $key doesn't have a strategy mentioned.
        if (count($values) < 2) {
            $values = [
                self::STRATEGY_IGNORE,
                $key,
            ];
        }
        for ($i = 2; $i < 5; $i++) {
            if (!isset($values[$i])) {
                $values[$i] = null;
            }
        }
        return $values;
    }

    /**
     * Replacement based on inherit-from-existing logic.
     *
     * @param mixed $runtime_value
     *  Runtime Value as generated by ES, based on user config and template.
     * @param mixed $existing
     *  Corresponding Existing Value found in config.
     *
     * @return array|null
     *  Inherits the value from config, if it is absent in runtime value.
     */
    private function implementStrategyInheritFromExisting($runtime_value, $existing = null)
    {
        if ($existing === null) {
            return $runtime_value;
        }
        $output = null;
        if (is_array($runtime_value)) {
            $output = Utilities::mergeData($runtime_value, $existing);
        } else {
            $output = $runtime_value ?? $existing;
        }
        return $output;
    }

    /**
     * Replace values base don encoded strategy in array keys.
     *
     * @param $values
     *  Incoming values.
     * @param $existing
     *  Values in existing configuration.
     *
     * @return mixed
     *  Adjusted values after corresponding replacement strategy has been done.
     */
    private function adjustValuesFromExistingConfig($values, $existing)
    {
        // Traverse over each high level keys and prepare
        // data for the corresponding placeholder strategy.
        foreach ($values as $key => $value) {
            list($strategy, $actual_key) = $this->getPlaceholderStrategyForKey($key);
            $existing_value = $existing[$actual_key] ?? null;
            switch ($strategy) {
                case self::STRATEGY_IGNORE:
                    break;

                case self::STRATEGY_INHERIT_FROM_EXISTING:
                    $values[$actual_key] = $this->implementStrategyInheritFromExisting($value, $existing_value);
                    // Since the runtime $key is an encoded key,
                    // we remove it from values.
                    unset($values[$key]);
            }

            // Dig deeper, in case a strategy is defined in top level keys.
            if (isset($values[$actual_key]) && is_array($values[$actual_key])) {
                $values[$actual_key] = $this->adjustValuesFromExistingConfig($values[$actual_key], $existing_value);
            }
        }
        return $values;
    }
}
