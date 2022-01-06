<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TunggakanTotal extends BaseController
{
    protected $curl;
    public function __construct()
    {
        $this->curl = service('curlrequest');
    }



    public function index()
    {
        $data = [
            'title' => "Total Tunggakan",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Laporan Tunggakan', 'Total Tunggakan'],
            'tunggakan' => [],
            'termYear' => null,
            'paymentOrder' => null,
            'listTermYear' => $this->getTermYear(),
            'prodi' => [],
            'fakultas' => [],
            'angkatan' => [],
            'validation' => \Config\Services::validation(),
            'menu'=>$this->fetchMenu()
        ];
        // dd($data);

        return view('pages/tunggakanTotal', $data);
    }

    public function getTermYear()
    {
        $response = $this->curl->request("GET", "https://api.umsu.ac.id/Laporankeu/getTermYear", [
            "headers" => [
                "Accept" => "application/json"
            ],

        ]);

        return json_decode($response->getBody())->data;
    }

    public function prosesTunggakanTotal()
    {
        if (!$this->validate([
            'tahunAjar' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tahun Ajar Harus Diisi !',
                ]
            ],
            'tahap' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tunggakan Tahap Harus Diisi !',
                ]
            ],
        ])) {
            return redirect()->to('tunggakanTotal')->withInput();
        }

        $term_year_id = trim($this->request->getPost('tahunAjar'));
        $payment_order = trim($this->request->getPost('tahap'));

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu/getTotalTunggakan", [
            "headers" => [
                "Accept" => "application/json"
            ],
            "form_params" => [
                "termYearId" => $term_year_id,
                "tahap" => $payment_order
            ]
        ]);

        $fakultas = [];
        foreach (json_decode($response->getBody())->data as $f) {
            if (!in_array($f->FAKULTAS, $fakultas)) {
                array_push($fakultas, $f->FAKULTAS);
            }
        }

        $prodi = [];
        foreach (json_decode($response->getBody())->data as $k) {
            array_push($prodi, [
                "fakultas" => $k->FAKULTAS,
                "prodi" => $k->NAMA_PRODI
            ]);
        }

        $angkatan = [];
        foreach (json_decode($response->getBody())->data as $a) {
            if (!in_array($a->ANGKATAN, $angkatan)) {
                array_push($angkatan, $a->ANGKATAN);
            }
        }


        $data = [
            'title' => "Total Tunggakan",
            'appName' => "UMSU FM",
            'breadcrumb' => ['Home', 'Laporan Tunggakan', 'Total Tunggakan'],
            'tunggakan' => json_decode($response->getBody())->data,
            'termYear' => $term_year_id,
            'paymentOrder' => $payment_order,
            'listTermYear' => $this->getTermYear(),
            'prodi' => array_unique($prodi, SORT_REGULAR),
            'fakultas' => $fakultas,
            'angkatan' => $angkatan,
            'validation' => \Config\Services::validation(),
            'menu'=>$this->fetchMenu()
        ];

        session()->setFlashdata('success', 'Berhasil Memuat Data Tunggakan, Klik Export Untuk Download !');
        return view('pages/tunggakanTotal', $data);
    }

    public function cetakTunggakanTotal()
    {
        $term_year_id = trim($this->request->getPost('tahunAjar'));
        $payment_order = trim($this->request->getPost('tahap'));

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu/getTotalTunggakan", [
            "headers" => [
                "Accept" => "application/json"
            ],
            "form_params" => [
                "termYearId" => $term_year_id,
                "tahap" => $payment_order
            ]
        ]);

        $fakultas = [];
        foreach (json_decode($response->getBody())->data as $f) {
            if (!in_array($f->FAKULTAS, $fakultas)) {
                array_push($fakultas, $f->FAKULTAS);
            }
        }

        $prodi = [];
        foreach (json_decode($response->getBody())->data as $k) {
            array_push($prodi, [
                "fakultas" => $k->FAKULTAS,
                "prodi" => $k->NAMA_PRODI
            ]);
        }

        $angkatan = [];
        foreach (json_decode($response->getBody())->data as $a) {
            if (!in_array($a->ANGKATAN, $angkatan)) {
                array_push($angkatan, $a->ANGKATAN);
            }
        }

        $spreadsheet = new Spreadsheet();
        $col =   array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
        $row = 1;

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $row, "Rekap Tunggakan")->mergeCells("A" . $row . ":" . $col[2 + (count($angkatan) - 1)] . $row)->getStyle("A" . $row . ":" . $col[2 + (count($angkatan) - 1)] . $row)->getFont()->setBold(true);
        $spreadsheet->setActiveSheetIndex(0)->getStyle("A" . $row . ":" . $col[2 + (count($angkatan) - 1)] . $row)->getAlignment()->setHorizontal('center');
        $row = $row + 1;
        $no = 0;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A' . $row, 'No.')
            ->setCellValue('B' . $row, 'Fakultas / Prodi')->getStyle("A" . $row . ":" . "B" . $row)->getFont()->setBold(true);

        $a = [];
        foreach ($angkatan as $ang) {
            $a[$ang] = 0;
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($col[2 + ($no)] . $row, $ang)->getStyle($col[2 + ($no)] . $row)->getFont()->setBold(true);
            $no++;
        }

        $row = $row + 1;

        foreach ($fakultas as $fak) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, '')
                ->setCellValue('B' . $row, $fak)->getStyle("A" . $row . ":" . "B" . $row)->getFont()->setBold(true);
            $no = 0;
            foreach ($angkatan as $ang) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($col[2 + ($no)] . $row, '')->getStyle($col[2 + ($no)] . $row)->getFont()->setBold(true);
                $no++;
            }
            $row++;

            $urut = 1;
            foreach (array_unique($prodi, SORT_REGULAR) as $prd) {
                if ($fak == $prd['fakultas']) {
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $row, $urut)
                        ->setCellValue('B' . $row, $prd['prodi']);

                    $no = 0;
                    foreach ($angkatan as $ang) {
                        $nilai = 0;
                        foreach (json_decode($response->getBody())->data as $tung) {
                            ($ang == $tung->ANGKATAN && $prd['prodi'] == $tung->NAMA_PRODI) ? $nilai = $tung->NOMINAL : $nilai = $nilai;
                            if ($ang == $tung->ANGKATAN && $prd['prodi'] == $tung->NAMA_PRODI) {
                                $a[$ang] = $a[$ang] + $tung->NOMINAL;
                            }
                        }
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue($col[2 + ($no)] . $row, number_to_currency($nilai, 'IDR'));
                        $no++;
                    }
                    $urut++;
                    $row++;
                }
            }
        }
        $no = 0;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A' . $row, '')
            ->setCellValue('B' . $row, 'Tunggakan Per Angkatan')->getStyle("A" . $row . ":" . "B" . $row)->getFont()->setBold(true);
        foreach ($angkatan as $ang) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($col[2 + ($no)] . $row, number_to_currency($a[$ang], 'IDR'))->getStyle($col[2 + ($no)] . $row)->getFont()->setBold(true);
            $no++;
        }
        $row++;
        $totalTunggakkan = 0;
        foreach ($angkatan as $ang) {
            $totalTunggakkan = $totalTunggakkan + $a[$ang];
        }
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A' . $row, '')
            ->setCellValue('B' . $row, 'Total Tunggakan')->getStyle("A" . $row . ":" . "B" . $row)->getFont()->setBold(true);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $row, number_to_currency($totalTunggakkan, 'IDR'))->mergeCells("C" . $row . ":" . $col[2 + (count($angkatan) - 1)] . $row)->getStyle("C" . $row . ":" . $col[2 + (count($angkatan) - 1)] . $row)->getFont()->setBold(true);
        $spreadsheet->setActiveSheetIndex(0)->getStyle("C" . $row . ":" . $col[2 + (count($angkatan) - 1)] . $row)->getAlignment()->setHorizontal('center');


        $writer = new Xlsx($spreadsheet);
        $fileName = 'Data Total Tunggakan Mahasiswa';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
        // return $this->index('tunggakan');
    }
}
