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

    public function fromApi()
    {
        $result = [];
        $response = $this->curl->request("POST", "https://api.umsu.ac.id/baad/lapCama", [
            "headers" => [
                "Accept" => "application/json"
            ],
            'form_params' => [
                'angkatan' => '2021',
                'kelompok' => 'NonKedokteran',
                'filter' => 'fai',
            ],
        ]);
        // array_push($result, json_decode($response->getBody())->data);

        // $response1 = $this->curl->request("POST", "https://api.umsu.ac.id/baad/lapCama", [
        //     "headers" => [
        //         "Accept" => "application/json"
        //     ],
        //     'form_params' => [
        //         'angkatan' => '2021',
        //         'kelompok' => 'NonKedokteran',
        //         'prodi' => 202,
        //     ],
        // ]);
        // array_push($result, json_decode($response->getBody())->data);

        // $response2 = $this->curl->request("POST", "https://api.umsu.ac.id/baad/lapCama", [
        //     "headers" => [
        //         "Accept" => "application/json"
        //     ],
        //     'form_params' => [
        //         'angkatan' => '2021',
        //         'kelompok' => 'NonKedokteran',
        //         'prodi' => 12,
        //     ],
        // ]);
        // array_push($result, json_decode($response->getBody())->data);
        // $hasil = array();
        // foreach ($result as $array) {
        //     $hasil = array_merge($hasil, $array);
        // }

        // $result = array_merge(json_decode($response->getBody())->data, json_decode($response1->getBody())->data, json_decode($response2->getBody())->data);

        // return $hasil;
        return json_decode($response->getBody())->data;
    }
}
