<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data = [
            'title' => "Home",
            'appName' => "Rekap BAAD",
            'breadcrumb' => ['Home', 'Dashboard'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu()
        ];
        return view('pages/home', $data);
    }
}
