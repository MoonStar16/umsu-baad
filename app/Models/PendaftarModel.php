<?php

namespace App\Models;

use CodeIgniter\Model;

class PendaftarModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $curl;

    public function __construct()
    {
        $this->curl = service('curlrequest');
    }
    public function getFromApi()
    {
        $response = $this->curl->request("GET", "https://api.umsu.ac.id/Laporankeu/getTermYear", [
            "headers" => [
                "Accept" => "application/json"
            ],

        ]);

        return json_decode($response->getBody())->data;
    }

    public function getFromFunc()
    {
        $builder = $this->query('call users');
        // $builder = $this->callFunction('get_client_info');
        return $builder;
    }
}
