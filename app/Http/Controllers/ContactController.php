<?php


namespace App\Http\Controllers;


class ContactController extends WelcomeController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $welcomeData = $this->welcomeData;
        return view('contact.main', compact('welcomeData'));
    }
}
