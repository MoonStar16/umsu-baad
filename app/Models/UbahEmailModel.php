<?php

namespace App\Models;

use CodeIgniter\Model;

class UbahEmailModel extends Model
{
    protected $curl;

    public function __construct()
    {
        $this->curl = service('curlrequest');
    }

    public function getDosen()
    {
        $response = $this->curl->request("GET", "https://api.umsu.ac.id/Employee", [
            "headers" => [
                "Accept" => "application/json",
                'Authorization' => 'Bearer ' . getenv('client.id'),
            ]
        ]);
        return json_decode($response->getBody());
    }
    public function getMahasiswa($angkatan)
    {
        $response = $this->curl->request("GET", "https://api.umsu.ac.id/Simakad/mahasiswaByAngkatan?angkatan=$angkatan", [
            "headers" => [
                "Accept" => "application/json",
                'Authorization' => 'Bearer ' . getenv('client.id'),
            ]
        ]);
        return json_decode($response->getBody());
    }

    public function updateEmailMhs($data)
    {
        $response = $this->curl->request(
            "POST",
            "https://api.umsu.ac.id/utility/ubahEmailMhs",
            [
                "headers" => [
                    "Accept" => "application/json",
                    'Authorization' => 'Bearer ' . getenv('client.id'),
                ],
                "form_params" => [
                    "Nim" => $data['Nim'],
                    "Email_General" => $data['Email_General'],
                ]
            ]
        );
        return json_decode($response->getBody());
    }

    public function updateEmailDosen($data)
    {
        $response = $this->curl->request(
            "POST",
            "https://api.umsu.ac.id/utility/ubahEmailDosen",
            [
                "headers" => [
                    "Accept" => "application/json",
                    'Authorization' => 'Bearer ' . getenv('client.id'),
                ],
                "form_params" => [
                    "Employee_Id" => $data['Employee_Id'],
                    "Email_Corporate" => $data['Email_Corporate'],
                ]
            ]
        );
        return json_decode($response->getBody());
    }
}
