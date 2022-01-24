<?php

namespace App\Models;

use CodeIgniter\Model;

class PendaftarModel extends Model
{
    protected $curl;

    public function __construct()
    {
        $this->curl = service('curlrequest');
    }

    public function getListKelompok()
    {
        $response = $this->curl->request("GET", "https://api.umsu.ac.id/baad/filter", [
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
    }

    public function getFakultas($kelompok)
    {
        $response = $this->curl->request(
            "POST",
            "https://api.umsu.ac.id/baad/fakultas",
            [
                "headers" => [
                    "Accept" => "application/json"
                ],
                "form_params" => [
                    "kelompok" => $kelompok,
                ]
            ]
        );

        return json_decode($response->getBody())->data;
    }
}
