<?php

namespace App\Controllers;

use App\Models\IpkModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Ipk extends BaseController
{
    protected $ipkModel;
    protected $spreadsheet;

    public function __construct()
    {
        $this->ipkModel = new IpkModel();
        $this->spreadsheet = new Spreadsheet();
    }

    public function index()
    {
        $data = [
            'title' => "IPK Mahasiswa",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Mahasiswa', 'Data KRS Dan Nilai', 'IPK Mahasiswa'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listTermYear' => $this->ipkModel->getTermYear(),
            'termYear' => null,
            'entryYear' => null,
            'dataResult' => []
        ];
        // dd($data);

        return view('pages/ipk', $data);
    }

    public function proses()
    {
        if (!$this->validate([
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
            return redirect()->to('ipk')->withInput();
        }

        $data = array(
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
        );

        $lapIpk = $this->ipkModel->getLapIpk($data);
        // dd($lapIpk);
        $data = [
            'title' => "IPK Mahasiswa",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Mahasiswa', 'Data KRS Dan Nilai', 'IPK Mahasiswa'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listTermYear' => $this->ipkModel->getTermYear(),
            'termYear' => $data['tahunAjar'],
            'entryYear' => $data['tahunAngkatan'],
            'dataResult' => $lapIpk
        ];
        session()->setFlashdata('success', '<strong>' . count($lapIpk) . ' Data' . '</strong> Telah Ditemukan ,Klik Export Untuk Download!');
        return view('pages/ipk', $data);
    }

    public function exportIpk()
    {
        $data = array(
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
        );

        $lapIpk = $this->ipkModel->getLapIpk($data);
        $row = 1;
        $this->spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $row, 'IPK Mahasiswa')->mergeCells("A" . $row . ":M" . $row)->getStyle("A" . $row . ":I" . $row)->getFont()->setBold(true);
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
        foreach ($lapIpk as $ipk) {
            $this->spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, $no++)
                ->setCellValue('B' . $row, $ipk->NO_REGISTRASI)
                ->setCellValue('C' . $row, $ipk->NPM)
                ->setCellValue('D' . $row, $ipk->NAMA_LENGKAP)
                ->setCellValue('E' . $row, $ipk->Department_Id)
                ->setCellValue('F' . $row, $ipk->NAMA_PRODI)
                ->setCellValue('G' . $row, $ipk->KELAS)
                ->setCellValue('H' . $row, $ipk->KODE_MATKUL)
                ->setCellValue('I' . $row, $ipk->NAMA_MATKUL)
                ->setCellValue('J' . $row, $ipk->SKS)
                ->setCellValue('K' . $row, $ipk->NIDN)
                ->setCellValue('L' . $row, $ipk->NAMA_DOSEN)
                ->setCellValue('M' . $row, $ipk->TAHUN_AKADEMIK);
            $row++;
        }
        $writer = new Xlsx($this->spreadsheet);
        $fileName = 'IPK Mahasiswa TA ' . $ipk->TAHUN_AKADEMIK;

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
    }
}
