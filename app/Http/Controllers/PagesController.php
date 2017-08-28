<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Mail;
use Session;

class PagesController extends Controller
{
    public function getIndex()
    {
        $posts = Post::orderBy('created_at','desc')->limit(4)->get();
        # process variable date or params
        # talk to the model
        # receive from the model
        # compile or process data form the model if needed
        # pass that data to the correct view
        return view('pages/welcome')->withPosts($posts);
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
        return view('pages.contact');
    }

    public function postContact(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'subject' => 'min:3',
            'bodyMessage' => 'min:10'
            ]);

        $data = array(
            'email'     => $request->email,
            'subject'   => $request->subject,
            'bodyMessage'   => $request->message

        );

        // if send too many emails, use Mail::queue() would be better
        // bug fix: You must pass your variables to the closure using use($data)
        Mail::send('emails.contact', $data, function($message) use ($data){
            // set up for email(from, to, cc, bcc, etc.)
            $message->from($data['email']);
            $message->to('sunyonglincanada@gmail.com');
            $message->subject($data['subject']);

        });

        Session::flash('success', 'Your Email was Sent Successfully!');

        return redirect('/');
    }

}
