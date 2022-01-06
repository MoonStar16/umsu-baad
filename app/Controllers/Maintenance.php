<?php

namespace App\Controllers;

class Maintenance extends BaseController
{
    public function index()
    {
        $data = [
            'title' => "Under Maitenance",
            'appName' => "UMSU",
            'validation' => \Config\Services::validation(),
            'menu'=>$this->fetchMenu()
        ];
        return view('pages/maintenance', $data);
    }
}
