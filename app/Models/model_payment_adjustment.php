<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_payment_adjustment extends Model
{
    protected $table = 'tbl_payment_adjustment';
    protected $allowedFields = [''];
	
	public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

	public function advance_amnt($data)
    {
		try{        
            $builder = $this->db->table("view_adjustment_adjust")
                        ->select('*')
                        ->where('md5(prop_dtl_id::text)', $data['id'])
                        ->get();
						
           return $builder->getFirstRow('array');

        }catch(Exception $e){
            return $e->getMessage();   
        }
        
    }
	
	public function advance($data)
    {
		$sql = "SELECT *
			FROM tbl_payment_adjustment
			where prop_dtl_id=?";
			$ql= $this->query($sql, [$data]);
			$advn =$ql->getFirstRow('array');
	}
	
	public function payment_adjustment($data)
    {
		$resultAdv = $this->db->table('tbl_payment_adjustment')->
			insert([
				  "prop_dtl_id"=>$data["custm_id"],
				  "advance_amt"=>$data['advance_amount'],
				  "created_on"=>$data["date"],
				  "status"=>1
				  ]);
	}
	
}
?>