<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class grievance_details_model extends Model
{
    protected $db;
    protected $table = 'tbl_grievance_details';
    protected $allowedFields = [];
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    
    public function grievance_details($input)
    {
		$result = $this->db->table($this->table)->
        insert([
			  "grievance_type"=>($input["grievance_type"]!="")?$input["grievance_type"]:null,
			  "module"=>($input["module"]!="")?$input["module"]:null,
			  "grievance_mst_id"=>($input["grievance_id"]!="")?$input["grievance_id"]:null,
			  "ward_mstr_id"=>($input["ward_id"]!="")?$input["ward_id"]:null,
			  "unique_no"=>($input["search"]!="")?$input["search"]:null,
			  "doc_path"=>null,
			  "query"=>($input["query"]!="")?$input["query"]:null,
			  "mobile_no"=>($input["mobile_no"]!="")?$input["mobile_no"]:null,
			  "token_no"=>0,
			  "created_on"=>$input["created_on"],
			  "status"=>1
			  ]);
		//echo $this->getLastQuery();
		$result = $this->db->insertID();
			
		$input["token_no"]= "GRVCMPL".date('d').$result;

		$this->db->table($this->table)
				->where('id', $result)
				->set(['token_no' => $input["token_no"]])
				->update();
			
		$grvRspn = $this->db->table("tbl_grievance_response")->
		insert([
			  "grievance_details_id"=>$result,
			  "from_user_type_id"=>0,
			  "to_user_type_id"=>11,
			  "reply_details"=>null,
			  "created_on"=>$input["created_on"],
			  "status"=>1
			  ]);
			 
		$token_no = $input["token_no"];	
		
		return $token_no;

    }
	
	
	public function update_grievance_doc($token_no,$doc_path)
    {
		$this->db->table($this->table)
				->where('token_no', $token_no)
				->set(['doc_path' => $doc_path])
				->update();
	}
	
	
	
	public function grievance_query($input)
    {
		$result = $this->db->table($this->table)->
        insert([
			  "grievance_type"=>($input["grievance_type"]!="")?$input["grievance_type"]:null,
			  "query"=>($input["query"]!="")?$input["query"]:null,
			  "mobile_no"=>($input["mobile_no"]!="")?$input["mobile_no"]:null,
			  "token_no"=>0,
			  "created_on"=>$input["date"],
			  "status"=>1
			  ]);
		//echo $this->getLastQuery();
		$result = $this->db->insertID();
			
		$input["token_no"]= "GRVQR".date('d').$result;

		$this->db->table($this->table)
				->where('id', $result)
				->set(['token_no' => $input["token_no"]])
				->update();
		
		$grvRspn = $this->db->table("tbl_grievance_response")->
        insert([
			  "grievance_details_id"=>$result,
			  "from_user_type_id"=>0,
			  "to_user_type_id"=>11,
			  "created_on"=>$input["date"],
			  "status"=>1
			  ]);
			 
		$token_no = $input["token_no"];	
		
		return $token_no;

    }
	
	
	public function grievance_close($data)
    { 
        $result = $this->db->table($this->table)
					->where('token_no',$data['token'])
					->set(['status' => 0])
					->update();
        return $result;
        //echo $this->db->getLastQuery();
    }
	
	public function grievance_forwardTl($data)
    { 
        $result = $this->db->table($this->table)
					->where('token_no',$data['token_no'])
					->set(['status' => 2])
					->update();
		if($result){
			$sql1 ="SELECT id
			FROM tbl_grievance_details 
			where token_no='".$data['token_no']."'";
			$q = $this->db->query($sql1);        
			$result1 = $q->getResultArray()[0];
			
			if($result1){			
				$result3 = $this->db->table("tbl_grievance_response")
							->where('grievance_details_id',$result1['id'])
							->set(['status' => 2,
								'emp_id'=>$data['emp_details_id']
							])
							->update();
				
				if($result3){
					$grvRspn = $this->db->table("tbl_grievance_response")->
					insert([
						  "grievance_details_id"=>$result1['id'],
						  "from_user_type_id"=>11,
						  "to_user_type_id"=>4,
						  "reply_details"=>null,
						  "created_on"=>$data["created_on"],
						  "status"=>1
						  ]);
						  //echo $this->db->getLastQuery();
				}
			} 
			  
		}
        //echo $this->db->getLastQuery();
    }
	
	
	
	public function grievance_forwardPM($data)
    { 
        $sql1 ="SELECT id
			FROM tbl_grievance_details 
			where token_no='".$data['token_no']."'";
			$q = $this->db->query($sql1);        
			$result = $q->getResultArray()[0];
			
		if($result){
			$result1 = $this->db->table("tbl_grievance_response")
						->where('grievance_details_id',$result['id'])
						->where('to_user_type_id',4)
						->where('status',1)
						->set(['status' => 2,
							'emp_id'=>$data['emp_details_id']
						])
						->update();
			
			if($result1){
				$grvRspn = $this->db->table("tbl_grievance_response")->
				insert([
					  "grievance_details_id"=>$result['id'],
					  "from_user_type_id"=>4,
					  "to_user_type_id"=>3,
					  "reply_details"=>null,
					  "created_on"=>$data["created_on"],
					  "status"=>1
					  ]);
					  //echo $this->db->getLastQuery();
			}
		}
    }
	
	
	
	
	public function grievance_list($data)
    { 
        $sql="SELECT tbl1.grievance_type,tbl1.module,tbl1.unique_no,tbl1.doc_path,tbl1.query,tbl1.status,
		tbl1.mobile_no,tbl1.token_no,tbl2.grievance,tbl_ward_mstr.ward_no
		FROM tbl_grievance_details tbl1
		LEFT JOIN tbl_ward_mstr ON tbl1.ward_mstr_id = tbl_ward_mstr.id
		LEFT JOIN tbl_grievance_master tbl2 ON tbl1.grievance_mst_id = tbl2.id
		where tbl1.grievance_type='".$data['type']."' and tbl1.created_on::date between '".$data['from']."' and '".$data['to']."'";
        $q = $this->db->query($sql);        
        $result = $q->getResultArray();
		return $result;
        //echo $this->db->getLastQuery();
    }
	
	public function grievance_alllist($data)
    { 
        $sql="SELECT tbl1.grievance_type,tbl1.module,tbl1.unique_no,tbl1.doc_path,tbl1.query,tbl1.status,
		tbl1.mobile_no,tbl1.token_no,tbl2.grievance,tbl_ward_mstr.ward_no,tbl3.*
		FROM tbl_grievance_details tbl1
		LEFT JOIN tbl_ward_mstr ON tbl1.ward_mstr_id = tbl_ward_mstr.id
		LEFT JOIN tbl_grievance_master tbl2 ON tbl1.grievance_mst_id = tbl2.id
		LEFT JOIN tbl_grievance_response tbl3 ON tbl1.id = tbl3.grievance_details_id
		where tbl1.created_on::date between '".$data['from']."' and '".$data['to']."'";
        $q = $this->db->query($sql);        
        $result = $q->getResultArray();
		return $result;
        //echo $this->db->getLastQuery();
    }
	
	
	public function grievance_tllist($data)
    { 
        $sql="SELECT tbl1.grievance_type,tbl1.module,tbl1.unique_no,tbl1.doc_path,tbl1.query,tbl1.status,
		tbl1.mobile_no,tbl1.token_no,tbl2.grievance,tbl_ward_mstr.ward_no,tbl3.*
		FROM tbl_grievance_details tbl1
		LEFT JOIN tbl_ward_mstr ON tbl1.ward_mstr_id = tbl_ward_mstr.id
		LEFT JOIN tbl_grievance_master tbl2 ON tbl1.grievance_mst_id = tbl2.id
		LEFT JOIN tbl_grievance_response tbl3 ON tbl1.id = tbl3.grievance_details_id
		where tbl3.to_user_type_id=".$data['user_type'];
        $q = $this->db->query($sql);        
        $result = $q->getResultArray();
		return $result;
        //echo $this->db->getLastQuery();
    }
	
	
	public function grievance_sts($data)
    { 
        $sql="SELECT tbl1.id,tbl1.grievance_type,tbl1.module,tbl1.unique_no,tbl1.doc_path,tbl1.query,tbl1.status,
		tbl1.mobile_no,tbl1.token_no,tbl2.grievance,tbl_ward_mstr.ward_no
		FROM tbl_grievance_details tbl1
		LEFT JOIN tbl_ward_mstr ON tbl1.ward_mstr_id = tbl_ward_mstr.id
		LEFT JOIN tbl_grievance_master tbl2 ON tbl1.grievance_mst_id = tbl2.id
		where tbl1.token_no='".$data['token']."'";
        $q = $this->db->query($sql);        
        $result = $q->getResultArray()[0];
        return $result;
        //echo $this->db->getLastQuery();
    }
	
	public function grievance_chat($data)
    { 
        $sql="SELECT tbl1.*,tbl2.emp_name,tbl2.middle_name,tbl2.last_name
		FROM tbl_grievance_response tbl1
		left join tbl_emp_details tbl2 on tbl2.id = tbl1.emp_id
		where grievance_details_id='".$data."'
		order by id ASC";
        $q = $this->db->query($sql);        
        $result = $q->getResultArray();
        return $result;
        //echo $this->db->getLastQuery();
    }
	
	
	public function grievance_replay($data)
    { 
		$result = $this->db->table($this->table)
					->where('token_no',$data['token_no'])
					->set(['status' => 3])
					->update();
		if($result){
			$sql1 ="SELECT id
				FROM tbl_grievance_details 
				where token_no='".$data['token_no']."'";
				$q = $this->db->query($sql1);        
				$result1 = $q->getResultArray()[0];
				
			if($result1){
				$result2 = $this->db->table("tbl_grievance_response")
						->where('grievance_details_id',$result1['id'])
						->where('status',1)
						->set(['status' => 3,
							'emp_id'=>$data['emp_details_id'],
							'remarks'=>$data['reply'],
							'reply_date'=>$data['date']
						])
						->update();
						echo $this->db->getLastQuery();
			}
		}
	}
	
	
}