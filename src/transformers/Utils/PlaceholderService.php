<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Utilities\Utilities;
use Symfony\Component\Yaml\Yaml;

class PlaceholderService
{

    const REUSE_OR_CREATE_VALUE = '__REUSE__';

    const CREATE_NEW_VALUE = '__NEW__';

    const STRATEGY_EDT = '__edt';
    const STRATEGY_ETD = '__edt';
    const STRATEGY_DET = '__det';
    const STRATEGY_DTE = '__dte';
    const STRATEGY_TDE = '__tde';
    const STRATEGY_TED = '__ted';

    protected const CHILD_REFERENCE = '__CHILD_REF__';

    // es.yml > existing configuration > template.yml
    const STRATEGY_INHERIT_FROM_EXISTING = '__';

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
            $existing = $existing ?? [];
            if (isset($results['id']) && $results['id'] == 'block_content.card.default') {
                $a = 1;
            }
            $results = $this->adjustValuesFromExistingConfig($results, $existing);
            $results = $this->postTransformValues($results, $existing);
            $return[$file] = $results;
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
        $values_copy = $values;
        // Traverse over each high level keys and prepare
        // data for the corresponding placeholder strategy.
        foreach ($values as $key => $value) {
            if ($key == 'content') {
                $c = 1;
            }
            list($strategy, $actual_key) = $this->getPlaceholderStrategyForKey($key);
            $e = $values_copy[$actual_key] ?? null;
            $d = $existing[$actual_key] ?? null;
            $t = $value;
            switch ($strategy) {
                case self::STRATEGY_IGNORE:
                    break;

                case self::STRATEGY_EDT:
                case self::STRATEGY_ETD:
                case self::STRATEGY_DTE:
                case self::STRATEGY_DET:
                case self::STRATEGY_TDE:
                case self::STRATEGY_TED:
                case self::STRATEGY_INHERIT_FROM_EXISTING:
                    $v = $this->implementStrategy($strategy, $e, $d, $t);
                    if ($actual_key !== $key) {
                        unset($values[$key]);
                    }
                    $values[$actual_key] = $v;
                    break;
            }
            if (!empty($values[$actual_key]) && is_array($values[$actual_key])) {
                $values[$actual_key] = $this->adjustValuesFromExistingConfig($values[$actual_key], $d);
            }
        }
        return $values;
    }

    /**
     * Find the value based on replacement strategy.
     *
     * @param string $strategy
     *   The strategy to be used.
     *
     * @param mixed $e
     *   Value from ES Configuration (user provided).
     * @param mixed $d
     *   Value from Drupal config files.
     * @param mixed $t
     *   Value from template.
     *
     * @return mixed|null
     *   One of the values provided based on the strategy.
     */
    private function implementStrategy(string $strategy, $e, $d, $t)
    {
        $val = null;
        $values = [];
        switch ($strategy) {
            case self::STRATEGY_INHERIT_FROM_EXISTING:
            case self::STRATEGY_EDT:
                $values = [$e, $d, $t];
                break;
            case self::STRATEGY_ETD:
                $values = [$e, $t, $d];
                break;
            case self::STRATEGY_DTE:
                $values = [$d, $t, $e];
                break;
            case self::STRATEGY_DET:
                $values = [$d, $e, $t];
                break;
            case self::STRATEGY_TDE:
                $values = [$t, $d, $e];
                break;
            case self::STRATEGY_TED:
                $values = [$t, $e, $d];
                break;
        }
        foreach ($values as $val) {
            if ($val !== null) {
                break;
            }
        }
        return $val;
    }
}
