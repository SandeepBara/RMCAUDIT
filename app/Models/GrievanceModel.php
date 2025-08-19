<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class GrievanceModel extends Model
{
    protected $db;
    protected $table = 'tbl_grievance_master';
    protected $allowedFields = ['id','grievance_name','module','status'];
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    
    public function grievanceList($where)
    {
        $sql="select * from tbl_grievance_master where module='".$where."'";
        $run=$this->db->query($sql);
		//echo $this->db->getLastQuery();
        $result=$run->getResultArray();
        return $result;

    }

}