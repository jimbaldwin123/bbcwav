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
        $start = 1;
        $end = $start + $duration;
        $stream = [];
        $i=0;
        foreach($files as $file){
            $i++;
            $f = 'outfile'.$i.'.wav';
            print $file . ' ' . $f . '<br>';
            $extractor = new Extractor($this->download_folder.$file);
            $extractor->getChunk($start,$end);
            $extractor->saveChunk($start,$end,$f);
        }
return;
        $extractor = new Extractor($this->download_folder.$files[0]);
        $size = $extractor->getChunkSize($start,$end);
        $extractor->wav->setDataChunkSize($size);
        $chunk = $extractor->wav->headersToString();
        dd($chunk);
        $chunk .= $extractor->wav->getWavChunk($start, $end);

        $file = @fopen($this->download_folder.'outfile1.wav', 'wb');
        if ($file === false) {
            throw new \Exception('Unable to create the file on the disk');
        }
        fwrite($file, $chunk);
        fclose($file);
    }
}
