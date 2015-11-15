<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Storage;

class HomeController extends Controller
{
    /**
     * Return a listing of the resources.
     *
     * @return Response
     */
    private function cleanUpFile($filename){


    }


    public function index()
    {
        $data_jobs    = str_getcsv(Storage::get('officialRemovedFillerX.csv'));
        $data_stud = str_getcsv(Storage::get('studentRemovedFillerX.csv'));
        var_dump($data_jobs);
        echo(count($data_jobs));
        echo(' :: ');
        echo(count($data_stud[1]));


        $practice_string_array = ['hello','Goodbye!','goodbye','no','yes'];

        $array = array();
        for ($i = 0; $i <= ((count($practice_string_array)) -1) ; $i++) {
            $practice_string_array[$i] = strtolower($practice_string_array[$i]);
            $practice_string_array[$i] = trim( preg_replace( "/[^0-9a-z]+/i", " ", $practice_string_array[$i] ) );
            if (array_key_exists(strtolower($practice_string_array[$i]), $array)) {
                $array[$practice_string_array[$i]] += 1;
            } else {
                $array[$practice_string_array[$i]] = 1;
            }
        }
        $my_size = count($array);
        echo('Most Common Words:   File Size:');
        echo( count($practice_string_array));
        echo('<br>');
        echo('<br>');
        echo('<br>');

        foreach ($array as $key => $value){
            $akey[$key] = $value;
        }
        array_multisort($akey, SORT_DESC, $array);
        foreach ($array as $key => $value){
            echo($key);
            echo(' :: ');
            echo($value);
            echo('<br>');
        }

        echo('<br>');
        echo('<br>');
        echo("A WebApp by TaDA and Company");
        return view('home');
    }
}
