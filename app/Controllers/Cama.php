<?php

namespace App\Controllers;

class Cama extends BaseController
{
    public function index()
    {
        $data = [
            'title' => "Cama",
            'appName' => "Rekap BAAD",
            'breadcrumb' => ['Home', 'Cama'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu()
        ];
        return view('pages/cama', $data);
    }
}
