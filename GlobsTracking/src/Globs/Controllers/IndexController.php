<?php namespace GlobsTracking\Globs\Controllers;

use GlobsTracking\Globs\FrontController;

class IndexController extends FrontController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        echo $this->twig->render('main.html');
    }

    public function test()
    {
        echo "hello from test";
    }
}
