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

        return;
    }


    public function index() # INDEX WILL GIVE US TOP WORDS IN EACH CATEGORY
    {

        $data_jobs    = str_getcsv(Storage::get('jobsFiller.csv'));
        $data_stud = str_getcsv(Storage::get('studFiller.csv'));

        #var_dump($data_jobs);



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

        $this->compareTwo("hello");

        return view('home');
    }

    private function compareTwo($filename){

       # $data_jobs    = str_getcsv(Storage::get('jobsFiller.csv'));
       # $data_stud = str_getcsv(Storage::get('studFiller.csv'));


        $data_jobs    = ['hello','Goodbye!','goodbye','no','yes'];
        $data_stud = ['hello','Goodbye!','goodbye','no','yes'];
        $sim_count = 0;
        for ($i = 0; $i < ((count($data_jobs))) ; $i++) {
            for ($j = 0; $j < ((count($data_stud))) ; $j++) {
                if ($data_stud[$j] == $data_jobs[$i]){
                    $sim_count += 1;
                }
            }
        }

        echo('Similar Words: ');
        echo($sim_count);
        echo('<br>');
        echo('<br>');
        echo("A WebApp by TaDA and Company");
        return view('home');


    }
}
