<?php

use Awcodes\Richie\Support\Converter;

if (! function_exists('richie')) {
    function richie(string | array | stdClass | null $content): Converter
    {
        return new Converter($content);
    }
}
