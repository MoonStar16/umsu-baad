<?php

namespace App\Controllers;

use App\Models\PendaftarModel;

class Pendaftar extends BaseController
{
    protected $pendaftarModel;

    public function __construct()
    {
        $this->pendaftarModel = new PendaftarModel();
    }

    public function index()
    {
        $data = [
            'title' => "Pendaftar",
            'appName' => "Rekap BAAD",
            'breadcrumb' => ['Laporan Penmaru', 'Data Calon Pendaftar'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listKelompok' => $this->pendaftarModel->getListKelompok(),
            'listTermYear' => $this->pendaftarModel->getTermYear(),
            'termYear' => null,
            'entryYear' => null,
        ];
        // dd($data);

        return view('pages/pendaftar', $data);
    }

    public function fakultas()
    {
        $kelompok = $this->request->getVar('kelompok');
        $dataFakultas = $this->pendaftarModel->getFakultas($kelompok);
        $lists = "";
        foreach ($dataFakultas as $row_fakultas) {
            $lists .= "<option value='" . $row_fakultas->fakNamaSingkat . "'>" . $row_fakultas->fakNamaResmi . "</option>";
        }

        echo $lists;
    }
}
