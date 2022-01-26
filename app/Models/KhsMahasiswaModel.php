<?php

namespace App\Models;

use CodeIgniter\Model;

class KhsMahasiswaModel extends Model
{
    protected $curl;

    public function __construct()
    {
        $this->curl = service('curlrequest');
    }

    public function getProdi()
    {
        $response = $this->curl->request("GET", "https://api.umsu.ac.id/baad/prodi", [
            "headers" => [
                "Accept" => "application/json"
            ],
        ]);
        return json_decode($response->getBody())->data;
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

    public function getLapKhsMahasiswa($data)
    {
        $response = $this->curl->request(
            "POST",
            "https://api.umsu.ac.id/baad/lapKhs",
            [
                "headers" => [
                    "Accept" => "application/json"
                ],
                "form_params" => [
                    "filter" => $data['fakultas'],
                    "termYearId" => $data['tahunAjar'],
                    "entryYearId" => $data['tahunAngkatan'],
                ]
            ]
        );

        return json_decode($response->getBody())->data;
    }
}
