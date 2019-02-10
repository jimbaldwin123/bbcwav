<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wav extends Model
{
    private $url;
    private $download_folder;

    public static function downloadFile($url, $path)
    {
        $newfname = $path;
        $file = fopen ($url, 'rb');
        if ($file) {
            $newf = fopen ($newfname, 'wb');
            if ($newf) {
                while(!feof($file)) {
                    fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
                }
            }
        }
        if ($file) {
            fclose($file);
        }
        if ($newf) {
            fclose($newf);
        }
    }

    public static function testWavExtract($filename)
    {
        $download_folder = "/home/ninge/Downloads/bbc/";
        $inputFile = $download_folder . $filename;
        $outputFile = $download_folder . 'chunk.wav';
        $start = 0 * 1000; // From 0 seconds
        $end = 2 * 1000; // To 2 seconds

        //dd($outputFile);
        // Extract the chunk and save it on the hard disk
        try {
            $extractor = new \Audero\WavExtractor\AuderoWavExtractor($inputFile);
            $extractor->downloadChunk($start, $end, $outputFile);
            echo 'Chunk extraction completed.';
        } catch (\Exception $ex) {
            echo 'An error has occurred: ' . $ex->getMessage();
        }
    }
}
