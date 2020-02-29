<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Utilities\Utilities;

class PlaceholderService {

    const PRESERVE_IF_AVAILABLE = '__PRESERVE__';

    protected $placeholders;

    public function set($name, $value) {
        $this->placeholders += $this->flattenArray($name, $value);
    }

    public function translate($input) {
      return strtr($input, $this->placeholders);
    }

    private function makePlaceholder($key) {
      return '{' . trim($key) . '}';
    }

    public function get($name, $default_value = '') {
        $key = $this->makePlaceholder($name);
        if (isset($this->placeholders[$key])) {
            return $this->placeholders[$key];
        }
        return $default_value;
    }

    protected function flattenArray($prefix, $input)
    {
        $result = array();
        if (!is_array($input)) {
          $result[$this->makePlaceholder($prefix)] = $input;
        }
        else {
            foreach ($input as $key => $value) {
                $new_key = $prefix . (empty($prefix) ? '' : '.') . $key;
                if (is_array($value)) {
                    $result = array_merge($result, $this->flattenArray($new_key, $value));
                }
                else {
                    $result[$this->makePlaceholder($new_key)] = $value;
                }
            }
        }
        return $result;
    }

    public function generateUUID() {
        return Utilities::generateUUID();
    }

    public function postTransform(&$items, $existing = [])
    {
        foreach ($items as $file => &$results) {
            foreach($results as $key => &$result) {
                if ($result == $this::PRESERVE_IF_AVAILABLE) {
                    $result = '';
                    if (isset($existing[$file][$key])) {
                        $result = $existing[$file][$key];
                    }
                    else {
                        // UUID is special,
                        // since we can't have it empty.
                        if ($key == 'uuid') {
                            $result = $this->generateUUID();
                        }
                    }
                }
            }
        }
    }
}
