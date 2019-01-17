<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use \App\Http\Controllers\SourceController;
use App\Wav as WavModel;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $sc = new SourceController();
        $sc->getWavs(['limit'=>1]);
        $this->assertTrue(true);
    }

}
