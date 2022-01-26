<?php

namespace App\Controllers;

use App\Models\KhsMahasiswaModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class KhsMahasiswa extends BaseController
{
    protected $khsMahasiswaModel;
    protected $spreadsheet;

    public function __construct()
    {
        $this->khsMahasiswaModel = new KhsMahasiswaModel();
        $this->spreadsheet = new Spreadsheet();
    }

    public function index()
    {
        $data = [
            'title' => "KHS Mahasiswa",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Mahasiswa', 'Data Mahasiswa', 'KHS Mahasiswa'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listProdi' => $this->khsMahasiswaModel->getProdi(),
            'listTermYear' => $this->khsMahasiswaModel->getTermYear(),
            'filter' => null,
            'termYear' => null,
            'entryYear' => null,
            'dataResult' => []
        ];
        // dd($data);

        return view('pages/khsMahasiswa', $data);
    }

    public function proses()
    {
        if (!$this->validate([
            'fakultas' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Prodi Harus Dipilih!',
                ]
            ],
            'tahunAngkatan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tahun Angkatan Harus Dipilih!',
                ]
            ],
            'tahunAjar' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tahun Ajar Harus Dipilih!',
                ]
            ],
        ])) {
            return redirect()->to('khsMahasiswa')->withInput();
        }

        $data = array(
            'fakultas' => trim($this->request->getPost('fakultas')),
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
        );

        $lapKhs = $this->khsMahasiswaModel->getLapKhsMahasiswa($data);
        // dd($lapKhs);
        $data = [
            'title' => "KHS Mahasiswa",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Mahasiswa', 'Data Mahasiswa', 'KHS Mahasiswa'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listProdi' => $this->khsMahasiswaModel->getProdi(),
            'listTermYear' => $this->khsMahasiswaModel->getTermYear(),
            'filter' => $data['fakultas'],
            'termYear' => $data['tahunAjar'],
            'entryYear' => $data['tahunAngkatan'],
            'dataResult' => $lapKhs
        ];
        session()->setFlashdata('success', '<strong>' . count($lapKhs) . ' Data' . '</strong> Telah Ditemukan ,Klik Export Untuk Download!');
        return view('pages/khsMahasiswa', $data);
    }

    public function exportKhsMahasiswa()
    {
        $data = array(
            'fakultas' => trim($this->request->getPost('fakultas')),
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
        );

        $lapKhs = $this->khsMahasiswaModel->getLapKhsMahasiswa($data);
        $row = 1;
        $this->spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $row, 'KHS Mahasiswa')->mergeCells("A" . $row . ":O" . $row)->getStyle("A" . $row . ":I" . $row)->getFont()->setBold(true);
        $row++;
        $this->spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A' . $row, 'No.')
            ->setCellValue('B' . $row, 'Nomor Registrasi')
            ->setCellValue('C' . $row, 'NPM')
            ->setCellValue('D' . $row, 'Nama Mahasiswa')
            ->setCellValue('E' . $row, 'Kode Prodi')
            ->setCellValue('F' . $row, 'Nama Prodi')
            ->setCellValue('G' . $row, 'Kelas')
            ->setCellValue('H' . $row, 'Kode Matkul')
            ->setCellValue('I' . $row, 'Matakuliah')
            ->setCellValue('J' . $row, 'SKS')
            ->setCellValue('K' . $row, 'NIDN')
            ->setCellValue('L' . $row, 'NAMA DOSEN')
            ->setCellValue('M' . $row, 'NILAI ANGKA')
            ->setCellValue('N' . $row, 'NILAI HURUF')
            ->setCellValue('O' . $row, 'TAHUN AKADEMIK')->getStyle("A2:O2")->getFont()->setBold(true);
        $row++;
        $no = 1;
        foreach ($lapKhs as $khs) {
            $this->spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, $no++)
                ->setCellValue('B' . $row, $khs->NO_REGISTRASI)
                ->setCellValue('C' . $row, $khs->NPM)
                ->setCellValue('D' . $row, $khs->NAMA_LENGKAP)
                ->setCellValue('E' . $row, $khs->Department_Id)
                ->setCellValue('F' . $row, $khs->NAMA_PRODI)
                ->setCellValue('G' . $row, $khs->KELAS)
                ->setCellValue('H' . $row, $khs->KODE_MATKUL)
                ->setCellValue('I' . $row, $khs->NAMA_MATKUL)
                ->setCellValue('J' . $row, $khs->SKS)
                ->setCellValue('K' . $row, $khs->NIDN)
                ->setCellValue('L' . $row, $khs->NAMA_DOSEN)
                ->setCellValue('M' . $row, $khs->NILAI_ANGKA)
                ->setCellValue('N' . $row, $khs->NILAI_HURUF)
                ->setCellValue('O' . $row, $khs->TAHUN_AKADEMIK);
            $row++;
        }
        $writer = new Xlsx($this->spreadsheet);
        $fileName = 'KHS Mahasiswa TA ' . $khs->TAHUN_AKADEMIK;

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
    }
}
