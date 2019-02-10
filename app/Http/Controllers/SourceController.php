<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wav as WavModel;
use \Audero\WavExtractor\AuderoWavExtractor as Extractor;
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
        WavModel::testWavExtract('07075024.wav');
    }

    public function wavConcatWrap()
    {
        $data = ['duration'=>1,'files'=>['07075053.wav','07075040.wav']];
        $this->wavConcat($data);
    }
    public function wavConcat($data)
    {
        $this->download_folder = "/home/ninge/Downloads/bbc/";
        $duration = $data['duration'];
        $files = $data['files'];
        $start = 0;
        $end = $start + $duration;
        $stream = [];
        $i=0;
        foreach($files as $file){
            $i++;
            $f = $this->download_folder .'outfile'.$i.'.wav';
            // print $file . ' ' . $f . '<br>';
            $extractor = new Extractor($this->download_folder.$file);
            //$extractor->getChunk($start,$end);
            $extractor->saveChunk($start,$end,$f);
        }
return;

    }
}
