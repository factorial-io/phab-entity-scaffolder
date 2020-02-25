<?php

namespace Phabalicious\Scaffolder\Transformers\Utils;

use Phabalicious\Method\TaskContextInterface;
use Phabalicious\Utilities\Utilities;

class PlaceholderService {

    const PRESERVE_IF_AVAILABLE = '__PRESERVE__';

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
                            $result = Utilities::generateUUID();
                        }
                    }
                }
            }       
        }
    }
}