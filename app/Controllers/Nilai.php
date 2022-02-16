<?php

namespace App\Controllers;

use PHPExcel;
use PHPExcel_IOFactory;
use App\Models\PendaftarModel;

class Nilai extends BaseController
{
    protected $pendaftarModel;
    public function __construct()
    {
        $this->pendaftarModel = new PendaftarModel();
    }

    public function index()
    {
        $data = array(
            'fakultas' => 'faperta',
            'tahunAjar' => 20201,
            'tahunAngkatan' => 2020,
        );

        $data = [
            'title' => "Nilai",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Nilai'],
            'menu' => $this->fetchMenu(),
            'nilai' => []
        ];
        return view('pages/nilai', $data);
    }

    public function prosesExcel()
    {
        $file = $this->request->getFile('fileexcel');
        if ($file) {
            $excelReader  = new PHPExcel();
            //mengambil lokasi temp file
            $fileLocation = $file->getTempName();
            //baca file
            $objPHPExcel = PHPExcel_IOFactory::load($fileLocation);
            //ambil sheet active
            $sheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            // dd($sheet);
            //looping untuk mengambil data
            // foreach ($sheet as $idx => $data) {
            //     //skip index 1 karena title excel
            //     if ($idx == 1) {
            //         continue;
            //     }
            //     $nama = $data['A'];
            //     $hp = $data['B'];
            //     $email = $data['C'];
            //     // insert data
            //     // $this->contact->insert([
            //     //     'nama' => $nama,
            //     //     'handphone' => $hp,
            //     //     'email' => $email
            //     // ]);
            //     echo $nama . "<br/>";
            // }
            $data = [
                'title' => "Nilai",
                'appName' => "UMSU",
                'breadcrumb' => ['Home', 'Nilai'],
                'menu' => $this->fetchMenu(),
                'nilai' => $sheet
            ];
        }
        session()->setFlashdata('message', 'Berhasil memuat data excel');
        return view('pages/nilai', $data);
    }
}
