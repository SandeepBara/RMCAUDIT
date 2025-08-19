<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_payment_adjust extends Model
{
    protected $db;
    protected $table = 'tbl_payment_adjust';
    protected $allowedFields = ['id', 'prop_dtl_id', 'advance_amt', 'created_on', 'status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function insertData($input){
        return $this->db->table($this->table)->insert($input);
        //return $insert_id = $this->db->insertID();
    }
	
	public function advance_amnt($data)
    {
		try{        
            $builder = $this->db->table("tbl_payment_adjust")
                        ->select('*')
                        ->where('md5(prop_dtl_id::text)', $data['id'])
                        ->get();
						
           return $builder->getResultArray()[0];

        }catch(Exception $e){
            return $e->getMessage();   
        }
        /*$sql = "SELECT *
		FROM view_adjustment_adjust
		where prop_dtl_id=?";
        $ql= $this->query($sql, [$data['id']]);
		if($ql){
			$result = $ql->getResultArray()[0];
			return $result;
		}else{
			return false;
		}*/
    }
	
	public function advance_adjst($data)
    {
		$resultadjst = $this->db->table('tbl_payment_adjust')->
			insert([
				  "prop_dtl_id"=>$input["custm_id"],
				  "advance_amt"=>$data['advc_adjst'],
				  "created_on"=>$input["date"],
				  "status"=>1
				  ]);
	}
    public function getAdvanced($data){
        try{
            $builder = $this->db->table($this->table)
                      ->select('COALESCE(SUM(advance_amt),0) as advance')
                      ->where('date(created_on) >=',$data['from_date'])
                      ->where('date(created_on) <=',$data['to_date'])
                      ->where('status',1)
                      ->get();
                    //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
           /*return  $builder['totalamount'];*/
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getAdvancedByPropId($prop_dtl_id){
        try{
            $builder = $this->db->table($this->table)
                       ->select('COALESCE(SUM(advance_amt),0) as advance')
                       ->where('prop_dtl_id',$prop_dtl_id)
                       ->where('status',1)
                       ->get();
            $builder = $builder->getFirstRow("array");
            return  $builder['advance'];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
}