<?php

namespace App\Controllers;

use App\Models\TotalMhsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TotalMhs extends BaseController
{
    protected $totalMhsModel;
    protected $spreadsheet;

    public function __construct()
    {
        $this->totalMhsModel = new TotalMhsModel();
        $this->spreadsheet = new Spreadsheet();
    }

    public function index()
    {
        $data = [
            'title' => "Total Mahasiswa Aktif",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Mahasiswa', 'Data Mahasiswa', 'Total Mahasiswa Aktif'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->totalMhsModel->getFakultas(),
            'listTermYear' => $this->totalMhsModel->getTermYear(),
            'filter' => null,
            'termYear' => null,
            'totalMhs' => [],
            'prodi' => [],
            'fakultas' => [],
            'angkatan' => [],
        ];
        // dd($data);

        return view('pages/totalMhs', $data);
    }

    public function proses()
    {
        if (!$this->validate([
            'tahunAjar' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tahun Ajar Harus Dipilih!',
                ]
            ],
        ])) {
            return redirect()->to('totalMhs')->withInput();
        }

        $data = array(
            'fakultas' => trim($this->request->getPost('fakultas')),
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
        );
        // dd($data);

        $lapTotalMhs = $this->totalMhsModel->getTotalMhs($data);
        // dd($lapTotalMhs);

        $fakultas = [];
        foreach ($lapTotalMhs as $f) {
            if (!in_array($f->FAKULTAS, $fakultas)) {
                array_push($fakultas, $f->FAKULTAS);
            }
        }

        $prodi = [];
        foreach ($lapTotalMhs as $k) {
            array_push($prodi, [
                "fakultas" => $k->FAKULTAS,
                "prodi" => $k->NAMA_PRODI
            ]);
        }

        $angkatan = [];
        foreach ($lapTotalMhs as $a) {
            if (!in_array($a->ANGKATAN, $angkatan)) {
                array_push($angkatan, $a->ANGKATAN);
            }
        }

        $data = [
            'title' => "Total Mahasiswa Aktif",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Mahasiswa', 'Data Mahasiswa', 'Total Mahasiswa Aktif'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->totalMhsModel->getFakultas(),
            'listTermYear' => $this->totalMhsModel->getTermYear(),
            'filter' => $data['fakultas'],
            'termYear' => $data['tahunAjar'],
            'totalMhs' => $lapTotalMhs,
            'prodi' => array_unique($prodi, SORT_REGULAR),
            'fakultas' => $fakultas,
            'angkatan' => $angkatan,
        ];
        // dd($lapTotalMhs);
        session()->setFlashdata('success', '<strong>' . count($lapTotalMhs) . ' Data' . '</strong> Telah Ditemukan ,Klik Export Untuk Download!');
        return view('pages/totalMhs', $data);
    }

    // public function exportTotalMhs()
    // {
    //     $data = array(
    //         'fakultas' => trim($this->request->getPost('fakultas')),
    //         'tahunAjar' => trim($this->request->getPost('tahunAjar')),
    //         'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
    //     );

    //     $lapTotalMhs = $this->totalMhsModel->getLapTotalMhs($data);
    //     $row = 1;
    //     $this->spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Total Mahasiswa Aktif')->mergeCells("A" . $row . ":M" . $row)->getStyle("A" . $row . ":I" . $row)->getFont()->setBold(true);
    //     $row++;
    //     $this->spreadsheet->setActiveSheetIndex(0)
    //         ->setCellValue('A' . $row, 'No.')
    //         ->setCellValue('B' . $row, 'Nomor Registrasi')
    //         ->setCellValue('C' . $row, 'NPM')
    //         ->setCellValue('D' . $row, 'Nama Mahasiswa')
    //         ->setCellValue('E' . $row, 'Kode Prodi')
    //         ->setCellValue('F' . $row, 'Nama Prodi')
    //         ->setCellValue('G' . $row, 'Kelas')
    //         ->setCellValue('H' . $row, 'Kode Matkul')
    //         ->setCellValue('I' . $row, 'Matakuliah')
    //         ->setCellValue('J' . $row, 'SKS')
    //         ->setCellValue('K' . $row, 'NIDN')
    //         ->setCellValue('L' . $row, 'NAMA DOSEN')
    //         ->setCellValue('M' . $row, 'TAHUN AKADEMIK')->getStyle("A2:M2")->getFont()->setBold(true);
    //     $row++;
    //     $no = 1;
    //     foreach ($lapTotalMhs as $totalMhs) {
    //         $this->spreadsheet->setActiveSheetIndex(0)
    //             ->setCellValue('A' . $row, $no++)
    //             ->setCellValue('B' . $row, $totalMhs->NO_REGISTRASI)
    //             ->setCellValue('C' . $row, $totalMhs->NPM)
    //             ->setCellValue('D' . $row, $totalMhs->NAMA_LENGKAP)
    //             ->setCellValue('E' . $row, $totalMhs->Department_Id)
    //             ->setCellValue('F' . $row, $totalMhs->NAMA_PRODI)
    //             ->setCellValue('G' . $row, $totalMhs->KELAS)
    //             ->setCellValue('H' . $row, $totalMhs->KODE_MATKUL)
    //             ->setCellValue('I' . $row, $totalMhs->NAMA_MATKUL)
    //             ->setCellValue('J' . $row, $totalMhs->SKS)
    //             ->setCellValue('K' . $row, $totalMhs->NIDN)
    //             ->setCellValue('L' . $row, $totalMhs->NAMA_DOSEN)
    //             ->setCellValue('M' . $row, $totalMhs->TAHUN_AKADEMIK);
    //         $row++;
    //     }
    //     $writer = new Xlsx($this->spreadsheet);
    //     $fileName = 'Total Mahasiswa Aktif TA ' . $totalMhs->TAHUN_AKADEMIK;

    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
    //     header('Cache-Control: max-age=0');

    //     // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
    //     $writer->save('php://output');
    // }
}
