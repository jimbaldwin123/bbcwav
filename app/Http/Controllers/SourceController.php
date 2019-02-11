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
        $this->download_folder = "/home/ninge/plex/bbc/";
        $limit = $data['limit'] ?? 10000;
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
     * @throws \Exception
     *
     */
    public function wavConcatWrap()
    {
        $filenames = glob('/home/ninge/Downloads/bbc/*.wav');
        $data = ['duration'=>300,'files'=>array_slice($filenames, 0, 100)];
        $this->wavConcat($data);
    }
    public function wavConcat($data)
    {
        $this->download_folder = "/home/ninge/Downloads/bbc/";
        $duration = $data['duration'];
        $files = $data['files'];
        $start = 500;
        $end = $start + $duration;
        $stream = [];
        $i=0;
        $chunk = '';
        foreach($files as $longfile){
            $file = basename($longfile);
            $i++;
            $f = $this->download_folder .'outfile'.$i.'.wav';
            print $file . ' ' . $f . '<br>';
            $extractor = new Extractor($this->download_folder.$file);
            // $extractor->getChunk($start,$end);
            // $extractor->saveChunk($start,$end,$f);

            $size = ($end - $start) * $extractor->wav->getDataRate() / 1000;
            $difference = $size % $extractor->wav->getChannelsNumber();
            if ($difference != 0) {
                $size += $difference;
            }
            $size = (int)$size;

            //$extractor->wav->setDataChunkSize($size);
            //$chunk = $this->wav->headersToString();
            $chunk .= $extractor->wav->getWavChunk($start, $end);
        }

            $size *= $i;
            $extractor->wav->setDataChunkSize($size);
            $header = $extractor->wav->headersToString();
            $hunk = $header . $chunk;

            $file = @fopen($this->download_folder.'concat.wav', 'wb');
            if ($file === false) {
                throw new \Exception('Unable to create the file on the disk');
            }
            fwrite($file, $hunk);
            fclose($file);

return;

    }
}
