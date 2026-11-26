<?php

namespace App\Models;

use CodeIgniter\Model;

class HapusTagihanModel extends Model
{
    protected $curl;

    public function __construct()
    {
        $this->curl = service('curlrequest');
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

    public function hapusTagihanLain($data)
    {
        $response = $this->curl->request(
            "POST",
            "https://api.umsu.ac.id/utility/biayaLain",
            [
                "headers" => [
                    "Accept" => "application/json",
                    'Authorization' => 'Bearer ' . getenv('client.id'),
                ],
                "form_params" => [
                    "Nim" => $data['Nim'],
                    "Term_Year_Id" => $data['Term_Year_Id'],
                    "Tagihan_Lain" => $data['Tagihan_Lain'],
                ]
            ]
        );
        return json_decode($response->getBody());
    }
}
