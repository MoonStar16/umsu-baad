<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // $file = "public/menu/menu.json";
        // $menu=file_get_contents(ROOTPATH.$file);

        // dd(json_decode($menu, true));
        $data = [
            'title' => "Home",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Dashboard'],
            'validation' => \Config\Services::validation(),
            'menu'=>$this->fetchMenu()
        ];
        return view('pages/home', $data);
    }

}
