<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wav as WavModel;

class SourceController extends Controller
{
    private $url;
    private $download_folder;
    public function getWavs($data = null)
    {
        $this->url = "http://bbcsfx.acropolis.org.uk/assets/";
        $this->download_folder = "/home/ninge/Downloads/bbc/";
        $limit = $data['limit'] ?? 100;
        if($limit){
            $wavs = WavModel::take($limit)->get();
        } else {
            $wavs = WavModel::get();
        }
        $count = 0;
        print $limit. "\n";
        foreach($wavs as $wav){
            \Log::debug('FILENAME ', [$count++, count($wavs),$wav->location]);
            WavModel::downloadFile($this->url . $wav->location,$this->download_folder . $wav->location);
        }

    }
    public function getWavSec()
    {
        WavModel::testWavExtract();
    }
}
