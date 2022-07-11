<?php


namespace App\Http\Controllers;


use App\FlowersItems;
use App\FlowersRubrics;
use DB;

class MainController extends WelcomeController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $welcomeData = $this->welcomeData;
        $flowersByRubric = $this->getFlowersByRubrics();
        $banners = DB::table('personalBanners')
            ->where('status', 'active')
            ->get();

        return view('index.main', compact(
            'flowersByRubric',
            'welcomeData',
            'banners'));
    }

}
