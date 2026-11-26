<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthGroupsUsersModel extends Model
{
    protected $table = 'auth_groups_users';
    protected $allowedFields = ['user_id', 'group_id'];
    protected $returnType = 'object';
    protected $useSoftDeletes = 'true';
}
