<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\AuthGroupsModel;
use App\Models\AuthGroupsUsersModel;

class ManajemenAkun extends BaseController
{
    protected $usersModel;
    protected $authGroupsUsersModel;
    protected $authGroupsModel;
    public function __construct()
    {
        $this->usersModel = new UsersModel();
        $this->authGroupsModel = new AuthGroupsModel();
        $this->authGroupsUsersModel = new AuthGroupsUsersModel();
    }
    public function index()
    {
        $data = [
            'menu' => $this->fetchMenu(),
            'appName' => "UMSU",
            'title' => "Manajemen Akun",
            'breadcrumb' => ['User', 'Manajemen Akun'],
            'akun' => $this->usersModel->getUser()->findAll(),
            'grup' =>  $this->authGroupsModel->findAll(),
            'validation' => \Config\Services::validation()
        ];
        return view('pages/manajemenAkun', $data);
    }

    public function edit($id)
    {
        $url = $this->request->getServer('HTTP_REFERER');
        $status = $this->request->getVar('active');
        $grup = $this->request->getVar('grup');

        if ($status) {
            if (!$this->validate([
                'grup' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Grup Harus Dipilih !',
                    ]
                ],
            ])) {
                return redirect()->to($url)->withInput();
            }
        }

        $data = array(
            'active' => $status == null ? 0 : 1
        );


        if ($this->usersModel->update($id, $data)) {
            if ($status) {
                $data_user_group = ['group_id' => $grup];
                $cek = $this->authGroupsUsersModel->getWhere(['user_id' => $id])->getResult();
                if (empty($cek)) {
                    $data_user_group['user_id'] = $id;
                    $this->authGroupsUsersModel->insert($data_user_group);
                } else {
                    $this->authGroupsUsersModel->where('user_id', $id)->set($data_user_group)->update();
                }
            }
            session()->setFlashdata('success', 'Data Akun Berhasil Diupdate !');
        } else {
            session()->setFlashdata('failed', 'Data Akun Gagal Diupdate !');
        }
        return redirect()->to($url);
    }
}
