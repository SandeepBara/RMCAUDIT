<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class TradeTradeItemsModel extends Model
{
    protected $db;
    protected $table = 'tbl_application_trade_items';
    protected $allowedFields = ['id','apply_licence_id','trade_items_id','emp_details_id','created_on','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function insertdataexcel($input){
         $builder = $this->db->table($this->table)
                            ->insert([
                               'apply_licence_id'=>$input["apply_licence_id"],
                               'trade_items_id'=>$input["trade_items_id"],                               
                               'emp_details_id'=>$input["emp_details_id"],
                               'created_on'=>$input["created_on"]
                            ]);
                           // echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
   }

  public function insertdata($input){
         $builder = $this->db->table($this->table)
                            ->insert([
                               'apply_licence_id'=>$input["apply_licence_id"],
                               'trade_items_id'=>$input["trade_items_id"],                               
                               'emp_details_id'=>$input["emp_details_id"],
                               'created_on'=>$input["created_on"]
                            ]);
                            /*echo $this->db->getLastQuery();*/
        return $insert_id = $this->db->insertID();
   }
   public function insertrenewdata($input){

         $sql_prop = "insert into tbl_application_trade_items(apply_licence_id,trade_items_id,emp_details_id,created_on,status) select '".$input['apply_licence_id']."',trade_items_id,
         '".$input['emp_details_id']."','".$input['created_on']."', '1' from tbl_licence_trade_items where licence_id='".$input['licence_id']."'";
        $this->db->query($sql_prop);
        //echo $this->db->getLastQuery();
        $con_dtl_id = $this->db->insertID();
       
         return $con_dtl_id;
     }

	public function getdatabyid_md5($applyid)
    {
        try
        {
          $builder = $this->db->table($this->table)
                    ->select('tbl_application_trade_items.*,tbl_trade_items_mstr.trade_item,tbl_trade_items_mstr.trade_code')
                    ->join('tbl_trade_items_mstr','tbl_trade_items_mstr.id=tbl_application_trade_items.trade_items_id and tbl_trade_items_mstr.status=1')
                    ->where('tbl_application_trade_items.status', 1)
                    ->where('md5(tbl_application_trade_items.apply_licence_id::text)', $applyid)
                    ->get();
          echo $this->db->getLastQuery();exit;
          return $builder->getResultArray();
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }
	
	
	public function gettrade_items($applyid,$nature_code)
    {
		$trade_items = "select tbl_apply_licence.nature_of_bussiness,tbl_trade_items_mstr.trade_item
					from tbl_apply_licence
					left join tbl_trade_items_mstr on tbl_trade_items_mstr.id=".$nature_code." and tbl_trade_items_mstr.status=1
					where tbl_apply_licence.status=1 and md5(tbl_apply_licence.id::text)='". $applyid."'";
        
					$q = $this->db->query($trade_items);   
					//echo $this->db->getLastQuery();		
					$result2 = $q->getResultArray();
					return $result2;
        
    }
	
    public function setStatusZeroForUpdate($apply_licence_id){
      try{
          return $builder = $this->db->table($this->table)
                           ->where('md5(apply_licence_id::text)',$apply_licence_id)
                           ->update([
                                      "status"=>0
                                    ]);

      }catch(Exception $e){
        echo $e->getMessage();
      }
    }
	public function checkIsExists($apply_licence_id,$trade_items_id){
    try{
        $builder = $this->db->table($this->table)
                  ->select('id')
                  ->where('md5(apply_licence_id::text)',$apply_licence_id)
                  ->where('trade_items_id',$trade_items_id)
                  ->get();
                  echo $this->db->getLastQuery();
        return $builder->getResultArray()[0];
    }catch(Exception $e){
      echo $e->getMessage();
    }
  }
  public function updateStatus($apply_licence_id,$trade_items_id){
    try{
        return $builder = $this->db->table($this->table)
                         ->where('md5(apply_licence_id::text)',$apply_licence_id)
                         ->where('trade_items_id',$trade_items_id)
                         ->update([
                                    "status"=>1
                                  ]);
    }catch(Exception $e){
      echo $e->getMessage();
    }
  }
  public function getTradeItemDetais($apply_licence_id){
    try{
          $builder = $this->db->table($this->table)
                    ->select("string_agg(trade_items_id::text, ',') AS trade_items_id")
                    ->where('apply_licence_id',$apply_licence_id)
                    ->where('status',1)
                    ->get();
          $builder = $builder->getFirstRow('array');
          return $builder['trade_items_id'];
    }catch(Exception $e){
      echo $e->getMessage();
    }
  }
}
?>