<?php

namespace App\Models;

use CodeIgniter\Model;

class MatkulModel extends Model
{
    protected $curl;

    public function __construct()
    {
        $this->curl = service('curlrequest');
    }

    public function getFakultas()
    {
        $response = $this->curl->request("GET", "https://api.umsu.ac.id/baad/filter", [
            "headers" => [
                "Accept" => "application/json",
                'Authorization' => 'Bearer ' . getenv('client.id'),
            ],
        ]);
        return json_decode($response->getBody())->data;
    }

    public function getTermYear()
    {
        $response = $this->curl->request("GET", "https://api.umsu.ac.id/Laporankeu/getTermYear", [
            "headers" => [
                "Accept" => "application/json",
                'Authorization' => 'Bearer ' . getenv('client.id'),
            ],
        ]);
        return json_decode($response->getBody())->data;
    }

    public function getLapMatkul($data)
    {
        // $response = $this->curl->request(
        //     "POST",
        //     "https://api.umsu.ac.id/Elearning/course",
        //     [
        //         "headers" => [
        //             "Accept" => "application/json",
        //             'Authorization' => 'Bearer ' . getenv('client.id'),
        //         ],
        //         "form_params" => [
        //             "filter" => $data['fakultas'],
        //             "tahunajar" => $data['tahunAjar'],
        //         ]
        //     ]
        // );
        $response = $this->curl->request(
            "POST",
            "https://developer.umsu.ac.id/elearning/course/offeredCourses",
            [
                "headers" => [
                    "Accept" => "application/json",
                    'x-api-key' =>  getenv('api.key'),
                    'x-secret-key' =>  getenv('secret.key'),
                ],
                "json" => [
                    "faculty" => $data['fakultas'],
                    "year" => $data['tahunAjar'],
                ]
            ]
        );
        return json_decode($response->getBody())->data;
    }
}
