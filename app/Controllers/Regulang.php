<?php

namespace App\Controllers;

use App\Models\RegulangModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Regulang extends BaseController
{
    protected $regulangModel;
    protected $spreadsheet;

    public function __construct()
    {
        $this->regulangModel = new RegulangModel();
        $this->spreadsheet = new Spreadsheet();
    }

    public function index()
    {
        $data = [
            'title' => "Per Angkatan",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Penmaru', 'Data Regitrasi Ulang', 'Per Angkatan'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->regulangModel->getFakultas(),
            'listTermYear' => $this->regulangModel->getTermYear(),
            'filter' => null,
            'termYear' => null,
            'entryYear' => null,
            'dataResult' => []
        ];
        // dd($data);

        return view('pages/regulang', $data);
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
            return redirect()->to('regulang')->withInput();
        }

        $data = array(
            'fakultas' => trim($this->request->getPost('fakultas')),
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
        );

        $lapRegulang = $this->regulangModel->getLapRegulang($data);
        // dd($lapRegulang);
        $data = [
            'title' => "Per Angkatan",
            'appName' => "UMSU",
            'breadcrumb' => ['Laporan Penmaru', 'Data Regitrasi Ulang', 'Per Angkatan'],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu(),
            'listFakultas' => $this->regulangModel->getFakultas(),
            'listTermYear' => $this->regulangModel->getTermYear(),
            'filter' => $data['fakultas'],
            'termYear' => $data['tahunAjar'],
            'entryYear' => $data['tahunAngkatan'],
            'dataResult' => $lapRegulang
        ];
        session()->setFlashdata('success', '<strong>' . count($lapRegulang) . ' Data' . '</strong> Telah Ditemukan ,Klik Export Untuk Download!');
        return view('pages/regulang', $data);
    }

    public function exportRegulang()
    {
        $data = array(
            'fakultas' => trim($this->request->getPost('fakultas')),
            'tahunAjar' => trim($this->request->getPost('tahunAjar')),
            'tahunAngkatan' => trim($this->request->getPost('tahunAngkatan')),
        );

        $lapRegulang = $this->regulangModel->getLapRegulang($data);
        $row = 1;
        $this->spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Data Mahasiswa')->mergeCells("A" . $row . ":I" . $row)->getStyle("A" . $row . ":I" . $row)->getFont()->setBold(true);
        $row++;
        $this->spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A' . $row, 'No.')
            ->setCellValue('B' . $row, 'Nomor Registrasi')
            ->setCellValue('C' . $row, 'Nama Lengkap')
            ->setCellValue('D' . $row, 'Email')
            ->setCellValue('E' . $row, 'Kode Prodi')
            ->setCellValue('F' . $row, 'Nama Prodi')
            ->setCellValue('G' . $row, 'Nomor Hp')
            ->setCellValue('H' . $row, 'Nama Ayah')
            ->setCellValue('I' . $row, 'Nama Ibu')
            ->setCellValue('J' . $row, 'Alamat')
            ->setCellValue('K' . $row, 'Angkatan')->getStyle("A2:K2")->getFont()->setBold(true);
        $row++;
        $no = 1;
        foreach ($lapRegulang as $mhs) {
            $noHp = (substr($mhs->mhsNoHp, 0, 3) == "+62") ? "0" . substr($mhs->mhsNoHp, 3, strlen($mhs->mhsNoHp)) : $mhs->mhsNoHp;
            $mobile = (substr($noHp, 0, 2) == "62") ? "0" . substr($noHp, 2, strlen($noHp)) : $noHp;
            $this->spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, $no++)
                ->setCellValue('B' . $row, $mhs->mhsNomorRegistrasi)
                ->setCellValue('C' . $row, $mhs->mhsNamaLengkap)
                ->setCellValue('D' . $row, $mhs->mhsEmail)
                ->setCellValue('E' . $row, $mhs->mhsProdiBankId)
                ->setCellValue('F' . $row, $mhs->prodiNamaResmi)
                ->setCellValue('G' . $row, $mobile)
                ->setCellValue('H' . $row, $mhs->mhsNamaAyah)
                ->setCellValue('I' . $row, $mhs->mhsNamaIbu)
                ->setCellValue('J' . $row, $mhs->mhsAlamat)
                ->setCellValue('K' . $row, $mhs->mhsAngkatan);
            $row++;
        }
        $writer = new Xlsx($this->spreadsheet);
        $fileName = 'Data Mahasiswa ' . $mhs->mhsAngkatan;

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
    }
}
