<?php

namespace App\Controllers;

use App\Models\FeederModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Feeder extends BaseController
{
    protected $feederModel;
    protected $spreadsheet;

    public function __construct()
    {
        $this->feederModel = new FeederModel();
        $this->spreadsheet = new Spreadsheet();
    }

    public function index()
    {
        $data = [
            'title' => "Data Feeder",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Mahasiswa', 'Data Feeder'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listTermYear' => $this->feederModel->getTermYear(),
            'filter' => null,
            'termYear' => null,
            'entryYear' => null,
            'dataResult' => []
        ];
        // dd($data);

        return view('pages/feeder', $data);
    }
}
