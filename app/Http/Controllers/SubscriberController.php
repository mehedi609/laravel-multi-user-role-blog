<?php

namespace App\Http\Controllers;

use App\Subscriber;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $request->validate([
           'subscriber_email' => 'required|email|unique:subscribers,email'
        ]);
        $subscriber = new Subscriber();
        $subscriber->email = $request->subscriber_email;
        $subscriber->save();

        Toastr::success('You are added to our subscriber list', 'Thank You!');

        return redirect(route('mainhome'));
    }
}
