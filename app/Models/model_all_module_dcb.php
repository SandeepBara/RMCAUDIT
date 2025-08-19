<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_all_module_dcb extends Model
{
	protected $db;
    protected $table = 'all_module_dcb';
    protected $allowedFields = [];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
	
	public function total_dcbprop(){
		$sql = "select * from all_module_dcb 
			order by id desc";
			$ql= $this->db->query($sql);
			//echo $this->db->getLastQuery();
			$result =$ql->getResultArray()[0];
			return $result;
    }
	
	
	public function total_dcbpropbyulbid($data){
		$sql = "select * from all_module_dcb 
			where ulb_id=$data
			order by id desc";
			$ql= $this->db->query($sql);
			//echo $this->db->getLastQuery();
			$result =$ql->getResultArray()[0];
			return $result;
    }
    
    
}
?>