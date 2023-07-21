<?php

namespace Michalsn\CodeIgniterPassage\Config;

use CodeIgniter\Config\BaseService;
use Michalsn\CodeIgniterPassage\Config\Passage as PassageConfig;
use Michalsn\CodeIgniterPassage\Passage;

class Services extends BaseService
{
    /**
     * Return the signed url class.
     *
     * @return Passage
     */
    public static function passage(?PassageConfig $config = null, bool $getShared = true): Passage
    {
        if ($getShared) {
            return static::getSharedInstance('passage', $config);
        }

        $config ??= config('Passage');

        return new Passage($config);
    }
}
