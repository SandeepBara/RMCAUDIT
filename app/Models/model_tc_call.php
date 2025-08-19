<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_tc_call extends Model
{
	protected $db;
    protected $table = 'tbl_tc_call';
	protected $allowedFields = [];
	
	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}
	
	public function callTc($data){
        $result = $this->db->table($this->table)
                ->insert([
						"ward_mst_id"=>$data['ward_mstr_id'],
						"ward_no"=>$data['ward_no'] ?? null,
						"prop_dtl_id"=>$data['prop_dtl_id'] ?? null,
						"holding_no"=>$data['holding_no'],
						"new_holding_no"=>$data['new_holding_no'],
						"address"=>$data['address'],
						"owner_name"=>$data['owner_name'],
						"mobile_no"=>$data['mobile_no'],
						"type"=>$data['type'],
						"shedule_date"=>$data['shedule_date'],
						"shedule_time"=>$data['shedule_time'],
						"created_on"=>$data['created_on'],
						"status"=> 1
				  ]);
				  
		//echo $this->db->getLastQuery();	  
		$result = $this->db->insertID();
		
		$result1 = $this->db->table("tbl_tc_call_inbox")
                ->insert([
					"tc_call_id"=> $result,
					"subject"=> "TC call by citizen for payment collection.",
					"from_user_type_id"=> 0,
					"to_user_type_id"=> 5,
					"tc_report_user_type_id"=> 7,
					"created_on"=> "NOW()",
					"status"=> 1
				]);
		//echo $this->db->getLastQuery();
		//exit;
        return $result1;
    }
	
	
	public function tcDetails($where)
    { 

        $sql="SELECT * FROM tbl_tc_call where $where";
        $q = $this->db->query($sql);        
        $result2 = $q->getFirstRow('array');
        return $result2;
        //echo $this->db->getLastQuery();
    }
	
	public function calltcDetails($data)
    { 

        $sql="SELECT * FROM tbl_tc_call where new_holding_no='".$data."'";
        $q = $this->db->query($sql);        
        $result2 = $q->getFirstRow('array');
		return $result2;
        //echo $this->db->getLastQuery();
    }
	
	
	public function callTcCitizen_list($emp_id,$from_date,$to_date,$user_type_mstr_id)
    { 
   // print_r($emp_ward);
      try{
			$builder = "select tbl_tc_call.*,tbl_emp_details.emp_name,tbl_tc_call_inbox.accepted_date_time,tbl_tc_call_inbox.subject,tbl_tc_call_inbox.remarks from tbl_tc_call
			left join tbl_tc_call_inbox on tbl_tc_call_inbox.tc_call_id=tbl_tc_call.id
			left join tbl_emp_details on tbl_emp_details.id=tbl_tc_call_inbox.accepted_by_emp_id
			where tbl_tc_call.created_on::date between '".$from_date."' and '".$to_date."' and tbl_tc_call.ward_mst_id In(select ward_mstr_id from tbl_ward_permission where emp_details_id=".$emp_id.") and tbl_tc_call_inbox.tc_report_user_type_id=".$user_type_mstr_id;
			$ql= $this->query($builder);
			//echo $this->getLastQuery();
			return $ql->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    
    }
	
	
	public function inbox_list($emp_id,$user_type_mstr_id)
    { 
   // print_r($emp_ward);
      try{
			$builder = "select count(tbl_tc_call.id) from tbl_tc_call
			left join tbl_tc_call_inbox on tbl_tc_call_inbox.tc_call_id=tbl_tc_call.id
			where tbl_tc_call.status=1 and tbl_tc_call.ward_mst_id In(select ward_mstr_id from tbl_ward_permission where emp_details_id=".$emp_id.") and tbl_tc_call_inbox.to_user_type_id=".$user_type_mstr_id;
			$ql= $this->query($builder);
			//echo $this->getLastQuery();
			return $ql->getFirstRow('array');
        }catch(Exception $e){
            return $e->getMessage();   
        }
    
    }
	
	
	public function inbox_details($emp_id,$user_type_mstr_id)
    { 
   // print_r($emp_ward);
      try{
			$builder = "select tbl_tc_call.*,tbl_tc_call_inbox.subject from tbl_tc_call
			left join tbl_tc_call_inbox on tbl_tc_call_inbox.tc_call_id=tbl_tc_call.id
			where tbl_tc_call.status=1 and tbl_tc_call.ward_mst_id In(select ward_mstr_id from tbl_ward_permission where emp_details_id=".$emp_id.") and tbl_tc_call_inbox.to_user_type_id=".$user_type_mstr_id;
			$ql= $this->query($builder);
			//echo $this->getLastQuery();
			return $ql->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    
    }
	
	public function accepted_history($emp_id)
    { 
   // print_r($emp_ward);
      try{
			$builder = "select tbl_tc_call.*,tbl_tc_call_inbox.accepted_date_time,tbl_tc_call_inbox.remarks,tbl_tc_call_inbox.subject from tbl_tc_call
			left join tbl_tc_call_inbox on tbl_tc_call_inbox.tc_call_id=tbl_tc_call.id
			where tbl_tc_call_inbox.accepted_by_emp_id=".$emp_id;
			$ql= $this->query($builder);
			//echo $this->getLastQuery();
			return $ql->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    
    }
	
	
	public function accept_request($data)
    { 
		$result = $this->db->table($this->table)
				->where($data['where'])
				->update(
					['status'=>2
				]);
				
		$sql = "select id from tbl_tc_call
				where ".$data['where'];
				$ql= $this->query($sql);
				$tc_call_id=$ql->getFirstRow('array');
        //echo $this->db->getLastQuery();
		
		$result1 = $this->db->table('tbl_tc_call_inbox')
				->where('tc_call_id', $tc_call_id)
				->update(
					['status'=>2,
					'accepted_by_emp_id'=>$data['emp_details_id'],
					'accepted_date_time'=>$data['date_time']
				]);
				//echo $this->db->getLastQuery();
		return $result1;
		
    }
	
	public function close_process($data)
    { 
		$result = $this->db->table($this->table)
				->where($data['where'])
				->update(
					['status'=>3
				]);
				
		$sql = "select id from tbl_tc_call
				where ".$data['where'];
				$ql= $this->query($sql);
				$tc_call_id=$ql->getFirstRow('array');
        //echo $this->db->getLastQuery();
		
		$result = $this->db->table('tbl_tc_call_inbox')
				->where('tc_call_id', $tc_call_id)
				->update(
					['remarks'=>$data['remarks'],
					'status'=>3
				]);
				//echo $this->db->getLastQuery();
		return $result;
		
    }
	


}