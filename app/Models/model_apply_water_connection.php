<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_apply_water_connection extends Model
{

    protected $table = 'tbl_apply_water_connection';
	protected $allowedFields = [''];
	
	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	
	}
    
	
	
	public function gatenewConnection($frm_date,$to_date){
		$new_cnnction = "SELECT count(id) as newCnnction FROM tbl_apply_water_connection
		WHERE apply_date between '".$frm_date."' AND '".$to_date."' AND status=1 AND connection_type_id=1
		";
		$ql= $this->query($new_cnnction);
		$resultcnnt =$ql->getResultArray()[0];
		return $resultcnnt;
    }  
	
	public function gateRegularizationcount($frm_date,$to_date){
		$regularization = "SELECT count(id) as regularizationcount FROM tbl_apply_water_connection
		WHERE apply_date between '".$frm_date."' AND '".$to_date."' AND status=1 AND connection_type_id=2
		";
		$ql= $this->query($regularization);
		$resultrgr =$ql->getResultArray()[0];
		return $resultrgr;
    }  

	

}