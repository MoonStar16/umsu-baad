<?php

namespace App\Controllers;

use App\Models\MatkulModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Matkul extends BaseController
{
    protected $matkulModel;
    protected $spreadsheet;

    public function __construct()
    {
        $this->matkulModel = new MatkulModel();
        $this->spreadsheet = new Spreadsheet();
    }

    public function index()
    {
        $data = [
            'title' => "Mata Kuliah",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Roster Akademik', 'Mata Kuliah'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->matkulModel->getFakultas(),
            'listTermYear' => $this->matkulModel->getTermYear(),
            'filter' => null,
            'termYear' => null,
            'dataResult' => []
        ];
        // dd($data);

        return view('pages/matkul', $data);
    }

    public function proses()
    {
        if (!$this->validate([
            'fakultas' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Fakultas Harus Dipilih!',
                ]
            ],
            'tahunAjar' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tahun Ajar Harus Dipilih!',
                ]
            ],
        ])) {
            return redirect()->to('matkul')->withInput();
        }

        $data = array(
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'fakultas' => trim($this->request->getPost('fakultas')),
        );

        $lapMatkul = $this->matkulModel->getLapMatkul($data);
        // dd($lapMatkul);
        $data = [
            'title' => "Mata Kuliah",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Roster Akademik', 'Mata Kuliah'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->matkulModel->getFakultas(),
            'listTermYear' => $this->matkulModel->getTermYear(),
            'filter' => $data['fakultas'],
            'termYear' => $data['tahunAjar'],
            'dataResult' => $lapMatkul
        ];
        session()->setFlashdata('success', '<strong>' . count($lapMatkul) . ' Data' . '</strong> Telah Ditemukan ,Klik Export Untuk Download!');
        return view('pages/matkul', $data);
    }

    public function exportMatkul()
    {
        $data = array(
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'fakultas' => trim($this->request->getPost('fakultas')),
        );

        $lapMatkul = $this->matkulModel->getLapMatkul($data);
        foreach ($lapMatkul as $matkulMahasiswa) {
            $stambuk = $matkulMahasiswa->ANGKATAN;
            $tahunAjaran = $matkulMahasiswa->TAHUN_AKADEMIK;
        }
        $row = 1;
        $this->spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Mata Kuliah Stambuk ' . $stambuk . ' TA. ' . $tahunAjaran)->mergeCells("A" . $row . ":K" . $row)->getStyle("A" . $row . ":I" . $row)->getFont()->setBold(true);
        $row++;
        $this->spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A' . $row, 'No.')
            ->setCellValue('B' . $row, 'Nomor Registrasi')
            ->setCellValue('C' . $row, 'NPM')
            ->setCellValue('D' . $row, 'Nama Mahasiswa')
            ->setCellValue('E' . $row, 'Nama Prodi')
            ->setCellValue('F' . $row, 'Kelas')
            ->setCellValue('G' . $row, 'SKS Diambil')
            ->setCellValue('H' . $row, 'SKS Diperoleh')
            ->setCellValue('I' . $row, 'IPS')
            ->setCellValue('J' . $row, 'IPK')
            ->setCellValue('K' . $row, 'TAHUN AJARAN')->getStyle("A2:K2")->getFont()->setBold(true);
        $row++;
        $no = 1;
        foreach ($lapMatkul as $matkul) {
            $this->spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, $no++)
                ->setCellValue('B' . $row, $matkul->Register_Number)
                ->setCellValue('C' . $row, $matkul->NPM)
                ->setCellValue('D' . $row, $matkul->NAMA_LENGKAP)
                ->setCellValue('E' . $row, $matkul->PRODI)
                ->setCellValue('F' . $row, $matkul->KELAS . $matkul->WAKTU)
                ->setCellValue('G' . $row, $matkul->SKS_DIAMBIL)
                ->setCellValue('H' . $row, $matkul->SKS_DIPEROLEH)
                ->setCellValue('I' . $row, $matkul->IPS)
                ->setCellValue('J' . $row, $matkul->IPK)
                ->setCellValue('K' . $row, $matkul->TAHUN_AKADEMIK);
            $row++;
        }
        $writer = new Xlsx($this->spreadsheet);
        $fileName = 'Mata Kuliah Stambuk ' . $matkul->ANGKATAN . ' TA. ' . $matkul->TAHUN_AKADEMIK;

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
    }
}
