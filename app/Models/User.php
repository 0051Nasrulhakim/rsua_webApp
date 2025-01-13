<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table            = 'user';
    protected $primaryKey       = 'id_user';
    protected $returnType       = 'array';
    protected $allowedFields    = [];

    public function getData($username, $password)
    {
        $enkripsiUser = getenv('AES_KEY_USERNAME');
        $enkripsiPassword = getenv('AES_KEY_PASSWORD');

        $sql = "select * from user where user.id_user=aes_encrypt('$username', '$enkripsiUser') and user.password=aes_encrypt('$password','$enkripsiPassword')";
        $query = $this->db->query($sql);
        
        return $query->getNumRows();
    }
}
