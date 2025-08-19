<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_consumer extends Model
{

    protected $table = 'tbl_consumer';
	protected $allowedFields = [''];
	
	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	
	}
    
	
	
	public function gateconsumercount($to_date){
		$consumer_count = "SELECT count(id) as consumercount FROM tbl_consumer
		WHERE created_on::date <='".$to_date."' AND status=1
		";
		$ql= $this->query($consumer_count);
		//echo $this->db->getLastQuery();
		$resultcnnt =$ql->getResultArray()[0];
		return $resultcnnt;
    }  
	
	

	

}