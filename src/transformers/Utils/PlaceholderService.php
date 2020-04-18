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
                $results = $this->reapplyExistingOverrides($results, $existing);
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

    private function reapplyExistingOverrides($values, array $existing)
    {
        $results = [];
        $user_config = [];
        $unset = [];
        // Remove duplicates from array and save it into another.
        foreach ($values as $key => $value) {
            if (substr($key, 0, 2) == '__') {
                $key = substr($key, 2);
                
                if (isset($values[$key])) {
                    $user_config[$key] = $values[$key];
                    $unset[] = $key;
                }
            }
        }
        foreach ($unset as $key) {
            unset($values[$key]);
        }
        
        foreach ($values as $key => $value) {
            if (substr($key, 0, 2) == '__') {
                $key = substr($key, 2);
                if (is_array($value)) {
                    if (isset($existing[$key])) {
                        $value = Utilities::mergeData($value, $existing[$key]);
                    }
                    if (isset($user_config[$key])) {
                        $value = Utilities::mergeData($value, $user_config[$key]);
                    }
                } else {
                    $value = $existing[$key] ?? $value;
                    $value = $values[$key] ?? $value;
                }
            }
            if (is_array($value)) {
                $value = $this->reapplyExistingOverrides($value, $existing[$key] ?? []);
            }
            $results[$key] = $value;
        }
        
        return $results;
    }
}
