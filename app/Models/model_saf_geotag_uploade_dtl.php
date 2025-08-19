<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_saf_geotag_uploade_dtl extends Model
{
    protected $db;
    protected $table = 'tbl_saf_geotag_upload_dtl';
    protected $allowedFields = [];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function gewardGeotag($ward_id)
    {
        $sql="select count(id) as no_of_geotag from tbl_saf_geotag_upload_dtl where ward_mstr_id=".$ward_id." and status=1";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;

    }
	
	public function geGeotag()
    {
        $sql="select count(id) as no_of_geotag from tbl_saf_geotag_upload_dtl where status=1";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;

    }
	
}
?> 