<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_system_name extends Model
{
	protected $db;
    protected $table = 'tbl_system_name';
    //protected $allowedFields = ['id','colony_name','colony_address'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }


    public function system_name($data)
    {
        $sql="select * from tbl_system_name where date::date <='".$data."' and status=1";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        return $result;

    }
    
}
?>
