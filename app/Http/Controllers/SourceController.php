<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wav as WavModel;
use \Audero\WavExtractor\AuderoWavExtractor as Extractor;
class SourceController extends Controller
{
    private $url;
    private $download_folder;

    public function __construct()
    {
        //parent::__construct();
        $this->download_folder = "/media/ninge/1TB/bbc/";
    }

    public function getWavs($data = null)
    {
        $this->url = "http://bbcsfx.acropolis.org.uk/assets/";
        $limit = $data['limit'];
        if($limit){
            $wavs = WavModel::take($limit)->get();
        } else {
            $wavs = WavModel::get();
        }
        $count = 0;
        print $limit. "\n";
        foreach($wavs as $wav){
            \Log::debug('FILENAME ', [$count++, count($wavs),$wav->location]);
            if(file_exists($this->download_folder.$wav->location)){
                continue;
            }
            WavModel::downloadFile($this->url . $wav->location,$this->download_folder . $wav->location);
        }

    }
    public function getWavSec()
    {
        WavModel::testWavExtract('07075024.wav');
    }

    public function getWavSave()
    {
        WavModel::testWavSave('07075024.wav');
    }


    /**
     * TODO clean up data table
     * make variable random duration
     * make variable random selection of wav files
     * use .env for folder settings etc.
     * @throws \Exception
     *
     */
    public function wavConcatWrap()
    {
        $filenames = array_map('basename',glob($this->download_folder. "0*.wav"));
        $data = ['duration'=>100,'files'=>$filenames, 'length'=>1000];
        $this->wavConcat($data);
    }
    public function wavConcat($data)
    {
        $duration = $data['duration'];

        $files = $data['files'];
        $length = $data['length'];

        $stream = [];
        $i=0;
        $chunk = '';
        $total_size = 0;
        for($i=0;$i<$length;$i++){
            $file = $files[rand(0,count($files))];
            $extractor = new Extractor($this->download_folder.$file);

            $duration = rand(50,500);
            $start = 500;
            $end = $start + $duration;
            print $duration . "\t" . $file . '<br>';

            $size = ($end - $start) * $extractor->wav->getDataRate() / 1000;
            $difference = $size % $extractor->wav->getChannelsNumber();
            if ($difference != 0) {
                $size += $difference;
            }
            $total_size += (int)$size;

            //$extractor->wav->setDataChunkSize($size);
            //$chunk = $this->wav->headersToString();
            $chunk .= $extractor->wav->getWavChunk($start, $end);
        }

            $extractor->wav->setDataChunkSize($total_size);
            $header = $extractor->wav->headersToString();
            $hunk = $header . $chunk;

            $file = @fopen($this->download_folder.'concat.wav', 'wb');
            if ($file === false) {
                throw new \Exception('Unable to create the file on the disk');
            }
            fwrite($file, $hunk);
            fclose($file);
    }
}
