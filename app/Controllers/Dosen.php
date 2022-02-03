<?php

namespace App\Controllers;

use App\Models\DosenModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Dosen extends BaseController
{
    protected $dosenModel;
    protected $spreadsheet;

    public function __construct()
    {
        $this->dosenModel = new DosenModel();
        $this->spreadsheet = new Spreadsheet();
    }

    public function index()
    {
        $data = [
            'title' => "Penugasan Dosen",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Roster Akademik', 'Penugasan Dosen'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->dosenModel->getFakultas(),
            'listTermYear' => $this->dosenModel->getTermYear(),
            'filter' => null,
            'termYear' => null,
            'dataResult' => []
        ];
        // dd($data);

        return view('pages/dosen', $data);
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
            return redirect()->to('dosen')->withInput();
        }

        $data = array(
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'fakultas' => trim($this->request->getPost('fakultas')),
        );

        $lapDosen = $this->dosenModel->getLapDosen($data);
        // dd($lapDosen);
        $data = [
            'title' => "Penugasan Dosen",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Roster Akademik', 'Penugasan Dosen'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->dosenModel->getFakultas(),
            'listTermYear' => $this->dosenModel->getTermYear(),
            'filter' => $data['fakultas'],
            'termYear' => $data['tahunAjar'],
            'dataResult' => $lapDosen
        ];
        session()->setFlashdata('success', '<strong>' . count($lapDosen) . ' Data' . '</strong> Telah Ditemukan ,Klik Export Untuk Download!');
        return view('pages/dosen', $data);
    }

    public function exportDosen()
    {
        $data = array(
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'fakultas' => trim($this->request->getPost('fakultas')),
        );

        $lapDosen = $this->dosenModel->getLapDosen($data);
        foreach ($lapDosen as $dosenMahasiswa) {
            $stambuk = $dosenMahasiswa->ANGKATAN;
            $tahunAjaran = $dosenMahasiswa->TAHUN_AKADEMIK;
        }
        $row = 1;
        $this->spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Penugasan Dosen Stambuk ' . $stambuk . ' TA. ' . $tahunAjaran)->mergeCells("A" . $row . ":K" . $row)->getStyle("A" . $row . ":I" . $row)->getFont()->setBold(true);
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
        foreach ($lapDosen as $dosen) {
            $this->spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, $no++)
                ->setCellValue('B' . $row, $dosen->Register_Number)
                ->setCellValue('C' . $row, $dosen->NPM)
                ->setCellValue('D' . $row, $dosen->NAMA_LENGKAP)
                ->setCellValue('E' . $row, $dosen->PRODI)
                ->setCellValue('F' . $row, $dosen->KELAS . $dosen->WAKTU)
                ->setCellValue('G' . $row, $dosen->SKS_DIAMBIL)
                ->setCellValue('H' . $row, $dosen->SKS_DIPEROLEH)
                ->setCellValue('I' . $row, $dosen->IPS)
                ->setCellValue('J' . $row, $dosen->IPK)
                ->setCellValue('K' . $row, $dosen->TAHUN_AKADEMIK);
            $row++;
        }
        $writer = new Xlsx($this->spreadsheet);
        $fileName = 'Penugasan Dosen Stambuk ' . $dosen->ANGKATAN . ' TA. ' . $dosen->TAHUN_AKADEMIK;

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
    }
}
