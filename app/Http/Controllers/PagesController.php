<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function getIndex()
    {
        # process variable date or params
        # talk to the model
        # receive from the model
        # compile or process data form the model if needed
        # pass that data to the correct view
        return view('pages/welcome');
    }

    public function getAbout()
    {
        $first      = 'Eric';
        $last       = 'Sun';

        $fullname   = $first . " " . $last;
        $email      = 'sunyonglincanada@gmail.com';
        $data['email'] = $email;
        $data['fullname'] = $fullname;
        return view('pages/about')->withData($data);
    }

    public function getContact()
    {
        return view('pages/contact');
    }

}
