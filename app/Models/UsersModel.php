<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['email', 'username', 'password_hash', 'reset_hash', 'reset_at', 'reset_expires', 'activate_hash', 'status', 'status_message', 'active', 'force_pass_reset', 'created_at', 'created_update', 'deleted_at'];
    protected $returnType = 'object';
    protected $useSoftDeletes = 'true';



    public function getUser()
    {
        $builder = $this->table('users');
        $builder->select('users.*, auth_groups.id as grupId, auth_groups.name as grupName, auth_groups.description as grupDesc, auth_groups_users.*');
        $builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id', 'LEFT');
        $builder->join('auth_groups', 'auth_groups.id  = auth_groups_users.group_id', 'LEFT');
        $builder->orderBy('users.id', 'DESC');
        return $builder;
    }

    public function getUserSearch($keyword)
    {
        $builder = $this->table('users');
        $builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id', 'LEFT');
        $builder->join('auth_groups', 'auth_groups.id  = auth_groups_users.group_id', 'LEFT');
        $builder->like('auth_groups.name', $keyword);
        $builder->orlike('users.username', $keyword);
        $builder->orlike('users.email', $keyword);
        $builder->orderBy('users.id', 'DESC');
        return $builder;
    }

    public function getSpecificUser($where)
    {
        $builder = $this->db->table('users');
        $builder->select('*');
        $builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $builder->join('auth_groups', 'auth_groups.id  = auth_groups_users.group_id');
        $builder->where($where);
        $query = $builder->get();
        return $query;
    }

    public function getProfile($where)
    {
        $builder = $this->db->table('users');
        $builder->select('*');
        $builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id', 'LEFT');
        $builder->join('auth_groups', 'auth_groups.id  = auth_groups_users.group_id', 'LEFT');
        $builder->join('dosen_pembimbing', 'dosen_pembimbing.dopingEmail  = users.email', 'LEFT');
        $builder->join('rumkit', 'rumkit.rumahSakitId  = dosen_pembimbing.dopingRumkitId', 'LEFT');
        $builder->where($where);
        $query = $builder->get();
        return $query;
    }
}
