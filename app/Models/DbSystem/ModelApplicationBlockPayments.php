<?php 
namespace App\Models\DbSystem;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class ModelApplicationBlockPayments  extends Model{
    protected $db;
    protected $table = 'tbl_application_block_payment';
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

}