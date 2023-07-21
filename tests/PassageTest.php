<?php

namespace Tests;

use CodeIgniter\Test\CIUnitTestCase;
use Config\App;
use Michalsn\CodeIgniterPassage\Passage;
use Michalsn\CodeIgniterPassage\Config\Passage as PassageConfig;
use Michalsn\CodeIgniterPassage\User;

/**
 * @internal
 */
final class PassageTest extends CIUnitTestCase
{
    protected Passage $psg;

    protected function setUp(): void
    {
        parent::setUp();

        $psgConfig = config(PassageConfig::class);
        $this->psg = new Passage($psgConfig);
    }

    public function testUser()
    {
        $this->assertInstanceOf(User::class, $this->psg->user);
    }
}
