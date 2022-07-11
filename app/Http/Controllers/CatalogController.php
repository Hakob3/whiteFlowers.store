<?php


namespace App\Http\Controllers;


use App\FlowersItems;

class CatalogController extends WelcomeController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $welcomeData = $this->welcomeData;

        $flowersByRubric = $this->getFlowersByRubrics();
        return view('catalog.main', compact(
            'welcomeData',
            'flowersByRubric'
        ));
    }
}
