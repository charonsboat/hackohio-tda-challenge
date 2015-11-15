<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Return a listing of the resources.
     *
     * @return Response
     */
    public function index()
    {
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
        echo('<br>');
        echo("Size: ");
        echo($my_size);
        echo('<br>');
        echo('<br>');
        foreach ($array as $key => $value){
            echo($key);
            echo(' :: ');
            echo($value);
            echo('<br>');
        }

        echo('<br>');
        echo('<br>');
        echo("Ben is doing work");
        return view('home');
    }
}
