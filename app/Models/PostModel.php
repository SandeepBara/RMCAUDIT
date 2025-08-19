<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class PostModel extends Model
{

    protected $table = 'curdtest';
 
    protected $allowedFields = ['name', 'email', 'pass', 'mobile', 'course'];
}