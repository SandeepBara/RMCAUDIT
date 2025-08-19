<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_govt_saf_officer_dtl extends Model
{
	protected $db;
    protected $table = 'tbl_govt_saf_officer_dtl';
    protected $allowedFields = ['id','building_type', 'mobile_no', 'designation', 'address'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function insertData($input){
        try{
            $this->db->table($this->table)
                                ->insert($input);
                //echo $this->db->getLastQuery();
            return $this->db->insertID();
        } catch(Exception $e) {

        }
    }
    
    public function getOwnerDetails($govt_saf_dtl_id)
    {
        $sql="select * from tbl_govt_saf_officer_dtl where md5(govt_saf_dtl_id::text)='$govt_saf_dtl_id'";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow('array');
        //echo $this->getLastQuery();
        return $result;

    }
}
?>
