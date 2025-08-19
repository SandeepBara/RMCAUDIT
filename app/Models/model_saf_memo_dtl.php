<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_saf_memo_dtl extends Model 
{
    protected $db;
    protected $table = 'tbl_saf_memo_dtl';
    protected $primaryKey = 'id';

    protected $allowedFields = ['id','saf_dtl_id','fy_mstr_id','effect_quarter','arv','quarterly_tax','memo_no','emp_details_id','created_on','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function InsertData($input)
    {
        $this->db->table($this->table)
                ->insert($input);
        return $this->db->insertID();

    }

    public function generate_assessment_final_memo($saf_dtl_id, $user_id)
    { 

        $sql="select * from generate_assessment_final_memo($saf_dtl_id, $user_id);";
        $sql = $this->db->query($sql);        
        $result = $sql->GetFirstRow('array');
        return $result;
    }

    public function generate_assessment_memo($saf_dtl_id, $user_id)
    { 

        $sql="select * from generate_assessment_memo($saf_dtl_id, $user_id);";
        $sql = $this->db->query($sql);        
        $result = $sql->GetFirstRow('array');
        return $result;
    }
    

    public function get_last_memo_no()
    { 

        $sql="SELECT id,memo_no FROM tbl_saf_memo_dtl order by id desc Limit 1";
        $q = $this->db->query($sql);        
        $result2 = $q->getResultArray();
        return $result2[0];
    }

    public function getMemo($input)
    {
        try
        {
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->where('memo_type', $input['memo_type'])
                        ->where('status', 1)
                        ->get();
            //echo $this->getLastQuery();
            return $builder->getFirstRow("array");

        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function generatesafmemono($saf_dtl_id, $fy_mstr_id, $fyear, $qtr,$arv,$quartely_tax,$memo_type,$login_emp_details_id,$created_on, $holding_no, $prop_dtl_id)
    {
        $builder = $this->db->table("tbl_saf_memo_dtl")
                            ->insert([
                                        "saf_dtl_id"=> $saf_dtl_id,
                                        "fy_mstr_id"=> $fy_mstr_id,
                                        "fy"=> $fyear,
                                        "effect_quarter"=> $qtr,
                                        "arv"=> $arv,
                                        "quarterly_tax"=> $quartely_tax,
                                        "memo_type"=> $memo_type,
                                        "emp_details_id"=> $login_emp_details_id,
                                        "created_on"=> $created_on,
                                        "status"=> 1,
                                        "holding_no"=> $holding_no,
                                        "prop_dtl_id"=> $prop_dtl_id,
                                    ]);
		//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }

    public function generategbsafmemono($govt_saf_dtl_id,$fy_mstr_id,$qtr,$arv,$quartely_tax,$memo_type,$login_emp_details_id,$created_on, $holding_no, $prop_dtl_id)
    {
        $builder = $this->db->table("tbl_saf_memo_dtl")
                            ->insert([
                                        "govt_saf_dtl_id"=> $govt_saf_dtl_id,
                                        "fy_mstr_id"=> $fy_mstr_id,
                                        "effect_quarter"=> $qtr,
                                        "arv"=> $arv,
                                        "quarterly_tax"=> $quartely_tax,
                                        "memo_type"=> $memo_type,
                                        "emp_details_id"=> $login_emp_details_id,
                                        "created_on"=> $created_on,
                                        "status"=> 1,
                                        "holding_no"=> $holding_no,
                                        "prop_dtl_id"=> $prop_dtl_id,
                                    ]);
		echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }

    public function updatememonoById($memo_id, $memo_no, $fy){
        $builder = $this->db->table($this->table)
                            ->where('id', $memo_id)
                            ->update(['memo_no'=> $memo_no, 'fy'=> $fy]);
                            //echo $this->db->getLastQuery();
        return $builder;

    }
    public function SAMDeactivatedBySafDtlId($saf_dtl_id){
        return $this->db->table($this->table)
                        ->where('saf_dtl_id', $saf_dtl_id)
                        ->update(['status'=> 0]);
    }

    public function DeactivateByMemoType($saf_dtl_id, $memo_type)
    {
        return $this->db->table($this->table)
                        ->where('saf_dtl_id', $saf_dtl_id)
                        ->where('memo_type', $memo_type)
                        ->update(['status'=> 0]);
    }

    public function SAMDeactivatedByGBSafDtlId($govt_saf_dtl_id)
    {
        return $this->db->table($this->table)
                        ->where('govt_saf_dtl_id', $govt_saf_dtl_id)
                        ->update(['status'=> 0]);
    }
    
    public function getMemoNoBySafId($saf_dtl_id, $memo_type)
    {
		try
        {
            $builder = $this->db->table($this->table)
                        ->select('id, memo_no, created_on')
                        ->where('saf_dtl_id', $saf_dtl_id)
                        ->where('memo_type', $memo_type)
                        ->get();
            //echo $this->getLastQuery();
            return $builder->getFirstRow("array");

        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    public function getAllMemo($input)
    {
		try
        {
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('saf_dtl_id', $input['saf_dtl_id'])
                        ->where('status', 1)
                        ->get();
            //echo $this->getLastQuery();exit;
            return $builder->getResultArray();

        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    public function getAllMemoByGovtSAFID($input)
    {
		try
        {
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('govt_saf_dtl_id', $input['govt_saf_dtl_id'])
                        ->where('status', 1)
                        ->get();
            //echo $this->getLastQuery();
            return $builder->getResultArray();

        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    public function getAllGovtMemo($input)
    {
		try
        {
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('govt_saf_dtl_id', $input['govt_saf_dtl_id'])
                        ->where('status', 1)
                        ->get();
            //echo $this->getLastQuery();
            return $builder->getResultArray();

        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }
	
	public function saf_memo($from_date,$to_date)
    {
		$demand_amnt = "SELECT count(saf_dtl_id) as sam_no FROM tbl_saf_memo_dtl
		WHERE status=1 AND 	memo_type='SAM' AND created_on::date BETWEEN'".$from_date."' and '".$to_date."'
		group by memo_type
		";
		$ql= $this->query($demand_amnt);
		$resultamnt =$ql->getResultArray()[0];
		//echo $this->db->getLastQuery();
		return $resultamnt;
    }  
	
	public function fam_memo($from_date,$to_date)
    {
		$fam_no = "SELECT count(saf_dtl_id) as fam_no FROM tbl_saf_memo_dtl
		WHERE status=1 AND 	memo_type='FAM' AND created_on::date BETWEEN '".$from_date."' and '".$to_date."'
		group by memo_type
		";
		$ql= $this->query($fam_no);
		$resultamnt =$ql->getResultArray()[0];
		//echo $this->db->getLastQuery();
		return $resultamnt;
    }  
	
	
	
	public function getwardsam($ward_id)
    {
        $sql="select count(id) as no_of_sam from tbl_saf_memo_dtl where ward_mstr_id=".$ward_id." and status=1 and memo_type='SAM'";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;

    }
	
	public function getsam()
    {
        $sql="select count(id) as no_of_sam from tbl_saf_memo_dtl where status=1 and memo_type='SAM'";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;

    }
	
	
	
	public function getwardfam($ward_id)
    {
        $sql="select count(id) as no_of_fam from tbl_saf_memo_dtl where ward_mstr_id=".$ward_id." and status=1 and memo_type='FAM'";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;

    }
	
	public function getfam()
    {
        $sql="select count(id) as no_of_fam from tbl_saf_memo_dtl where status=1 and memo_type='FAM'";
        $run=$this->db->query($sql);
        $result=$run->getResultArray()[0];
        //echo $this->getLastQuery();
        return $result;

    }
	
	// public function getMemoById_MD5($memo_id)
    // {
    //     try{   
    //         $builder = $this->db->table($this->table)
    //                     ->select('*')
    //                     ->where('md5(id::text)', $memo_id)
    //                     ->where('status', 1)
    //                     ->get();
    //         //echo $this->getLastQuery();
    //         return $builder->getFirstRow('array');
    //     }
    //     catch(Exception $e)
    //     {
    //         return $e->getMessage();   
    //     }
    // }
	public function getMemoById_MD5_old($memo_id)
    {
        try{   
            if (is_numeric($memo_id)) {
                $sql = "select 
                            tbl_saf_memo_dtl.*,
                            tbl_prop_dtl.new_holding_no 
                        from tbl_saf_memo_dtl 
                        inner join tbl_prop_dtl on tbl_saf_memo_dtl.prop_dtl_id=tbl_prop_dtl.id 
                        where 
                            tbl_saf_memo_dtl.id=".$memo_id." and 
                            tbl_saf_memo_dtl.status=1";
            } else {
                $sql = "select tbl_saf_memo_dtl.*,tbl_prop_dtl.new_holding_no from tbl_saf_memo_dtl inner join tbl_prop_dtl on  tbl_saf_memo_dtl.prop_dtl_id=tbl_prop_dtl.id where md5(tbl_saf_memo_dtl.id::text)='".$memo_id."' and tbl_saf_memo_dtl.status=1";
            }
            $run=$this->db->query($sql);
            //echo $this->getLastQuery();
            return $run->getFirstRow('array');
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }
    public function getMemoById_MD5($memo_id)
    {
        try{   
            if (is_numeric($memo_id)) {
                $sql = "select 
                            tbl_saf_memo_dtl.*,
                            tbl_prop_dtl.new_holding_no 
                        from tbl_saf_memo_dtl 
                        inner join tbl_prop_dtl on tbl_saf_memo_dtl.prop_dtl_id=tbl_prop_dtl.id 
                        where 
                            tbl_saf_memo_dtl.id=".$memo_id." and 
                            tbl_saf_memo_dtl.status=1";
            } else {

                $sql = "select tbl_saf_memo_dtl.*,tbl_prop_dtl.new_holding_no from tbl_saf_memo_dtl inner join tbl_prop_dtl on  tbl_saf_memo_dtl.prop_dtl_id=tbl_prop_dtl.id where md5(tbl_saf_memo_dtl.id::text)='".$memo_id."' and tbl_saf_memo_dtl.status=1";
            }
            $run=$this->db->query($sql);
            $data=$run->getFirstRow('array');
            if(!empty($data) && $data['prop_dtl_id']!=1)
            {
                return $data;
            }else{
                $sql = "select tbl_saf_memo_dtl.*,tbl_saf_dtl.prop_dtl_id,tbl_saf_dtl.holding_no from tbl_saf_memo_dtl inner join tbl_saf_dtl on  tbl_saf_memo_dtl.saf_dtl_id=tbl_saf_dtl.id where md5(tbl_saf_memo_dtl.id::text)='".$memo_id."' and tbl_saf_memo_dtl.status=1";
                $run=$this->db->query($sql);
                $data=$run->getFirstRow('array');
                if($data['prop_dtl_id']==0){
                    $samsql="select tbl_saf_memo_dtl.prop_dtl_id from tbl_saf_memo_dtl where memo_type='SAM' AND saf_dtl_id='".$data['saf_dtl_id']."'";
                    $samsql_run=$this->db->query($samsql);
                    $sam=$samsql_run->getFirstRow('array');
                   $data['prop_dtl_id']=$sam['prop_dtl_id'];
                }
                $propsql="select new_holding_no from tbl_prop_dtl where id=".$data['prop_dtl_id'];
                   $prop_run=$this->db->query($propsql);
                $prop=$prop_run->getFirstRow('array');
                $data['new_holding_no']=$prop['new_holding_no'];
                return $data;
            }
            return $run->getFirstRow('array');
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }
}               