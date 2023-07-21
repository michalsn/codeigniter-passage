<?php

namespace Michalsn\CodeIgniterPassage\Config;

use Michalsn\CodeIgniterPassage\Filters\PassageStateless;

class Registrar
{
    /**
     * Register the CodeIgniterPassage filter.
     */
    public static function Filters(): array
    {
        return [
            'aliases' => [
                'passageStateless' => PassageStateless::class,
            ],
        ];
    }
}
