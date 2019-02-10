<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\SourceController;

class WavConcatTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testGetWavSec()
    {
        $controller = new SourceController();
        $controller->getWavSec();
    }
    public function testWavConcat()
    {
        //$controller = new SourceController();
        //$controller->wavConcat(['duration'=>1,'files'=>['07075053.wav','07075040.wav']]);
    }
}
