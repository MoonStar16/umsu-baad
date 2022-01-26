<?php

namespace App\Controllers;

use App\Models\KrsAktifModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class KrsAktif extends BaseController
{
    protected $krsAktifModel;
    protected $spreadsheet;

    public function __construct()
    {
        $this->krsAktifModel = new KrsAktifModel();
        $this->spreadsheet = new Spreadsheet();
    }

    public function index()
    {
        $data = [
            'title' => "KRS Aktif",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Mahasiswa', 'Data Mahasiswa', 'KRS Aktif'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listProdi' => $this->krsAktifModel->getProdi(),
            'listTermYear' => $this->krsAktifModel->getTermYear(),
            'filter' => null,
            'termYear' => null,
            'entryYear' => null,
            'dataResult' => []
        ];
        // dd($data);

        return view('pages/krsAktif', $data);
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
            return redirect()->to('krsAktif')->withInput();
        }

        $data = array(
            'fakultas' => trim($this->request->getPost('fakultas')),
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
        );

        $lapKrsAktif = $this->krsAktifModel->getLapKrsAktif($data);
        // dd($lapKrsAktif);
        $data = [
            'title' => "KRS Aktif",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Mahasiswa', 'Data Mahasiswa', 'KRS Aktif'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listProdi' => $this->krsAktifModel->getProdi(),
            'listTermYear' => $this->krsAktifModel->getTermYear(),
            'filter' => $data['fakultas'],
            'termYear' => $data['tahunAjar'],
            'entryYear' => $data['tahunAngkatan'],
            'dataResult' => $lapKrsAktif
        ];
        session()->setFlashdata('success', '<strong>' . count($lapKrsAktif) . ' Data' . '</strong> Telah Ditemukan ,Klik Export Untuk Download!');
        return view('pages/krsAktif', $data);
    }

    public function exportKrsAktif()
    {
        $data = array(
            'fakultas' => trim($this->request->getPost('fakultas')),
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
        );

        $lapKrsAktif = $this->krsAktifModel->getLapKrsAktif($data);
        $row = 1;
        $this->spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $row, 'KRS Mahasiswa')->mergeCells("A" . $row . ":M" . $row)->getStyle("A" . $row . ":I" . $row)->getFont()->setBold(true);
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
            ->setCellValue('M' . $row, 'TAHUN AKADEMIK')->getStyle("A2:M2")->getFont()->setBold(true);
        $row++;
        $no = 1;
        foreach ($lapKrsAktif as $krs) {
            $this->spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, $no++)
                ->setCellValue('B' . $row, $krs->NO_REGISTRASI)
                ->setCellValue('C' . $row, $krs->NPM)
                ->setCellValue('D' . $row, $krs->NAMA_LENGKAP)
                ->setCellValue('E' . $row, $krs->Department_Id)
                ->setCellValue('F' . $row, $krs->NAMA_PRODI)
                ->setCellValue('G' . $row, $krs->KELAS)
                ->setCellValue('H' . $row, $krs->KODE_MATKUL)
                ->setCellValue('I' . $row, $krs->NAMA_MATKUL)
                ->setCellValue('J' . $row, $krs->SKS)
                ->setCellValue('K' . $row, $krs->NIDN)
                ->setCellValue('L' . $row, $krs->NAMA_DOSEN)
                ->setCellValue('M' . $row, $krs->TAHUN_AKADEMIK);
            $row++;
        }
        $writer = new Xlsx($this->spreadsheet);
        $fileName = 'KRS Mahasiswa Prodi ' . $krs->AKRONIM_PRODI . ' TA ' . $krs->TAHUN_AKADEMIK;

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
    }
}
