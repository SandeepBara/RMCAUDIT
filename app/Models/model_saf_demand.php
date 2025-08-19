<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_saf_demand extends Model 
{
    protected $db;
    protected $table = 'tbl_saf_demand';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'saf_dtl_id', 'saf_tax_id', 'fy_mstr_id', 'qtr', 'amount', 'balance', 'fine_tax', 'created_on', 'status','ward_mstr_id'];
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function insertData($input) {
        try{
            $this->db->table($this->table)
                            ->insert($input);
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }


    public function demand_details($data)
    {  
      try{
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1)
                        ->where('saf_dtl_id', $data["saf_id"])
                        ->get(); 

           return $builder->getResultArray()[0];


        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getsaftaxbyid($saf_dtl_id,$saf_tax_id){
        try{
            return $this->db->table($this->table)
                            ->select('*')
                            ->where('saf_dtl_id', $saf_dtl_id)
                            ->where('saf_tax_id', $saf_tax_id)
                            ->where('status', 1)                            
                            ->get()
                            ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getsaftaxnotpaidbyid($saf_dtl_id,$saf_tax_id){
        try{
            return $this->db->table($this->table)
                            ->select('*')
                            ->where('saf_dtl_id', $saf_dtl_id)
                            ->where('saf_tax_id', $saf_tax_id)
                            ->where('paid_status', 0)
                            ->where('status', 1)                      
                            ->get()
                            ->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	
	public function demand_detail($data) {
		try {
            $builder = $this->db->table("view_saf_demand")
                        ->select('*')
                        ->where('md5(saf_dtl_id::text)', $data['id'])
						->where('paid_status', 0)
						->orderBy("fy_id,qtr", "ASC")
                        ->get();
			//echo $this->db->getLastQuery();
            return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function demand_detail_by_id($saf_dtl_id) {
        try {
            $builder = $this->db->table("view_saf_demand")
                        ->select('*')
                        ->where('saf_dtl_id', $saf_dtl_id)
                        ->where('paid_status', 0)
                        ->orderBy("fy_id,qtr", "ASC")
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function demandDetailsById($saf_dtl_id) {
        try{
            $sql = "SELECT * FROM tbl_saf_demand WHERE status=1 AND paid_status=0 AND saf_dtl_id=".$saf_dtl_id." ORDER BY due_date ASC";
            return $this->db->query($sql)->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getFullDemandDtlBySafDtlId($input)
    {
		try{
            $sql = "SELECT
                    tbl_saf_demand.id as saf_tax_id, 
                    tbl_saf_demand.saf_dtl_id as saf_dtl_id, 
                    tbl_saf_demand.saf_tax_id as saf_tax_id, 
                    tbl_saf_demand.fy_mstr_id as fy_mstr_id,
                    view_fy_mstr.fy as fy,
                    tbl_saf_demand.qtr as qtr, 
                    tbl_saf_demand.amount as amount, 
                    tbl_saf_demand.balance as balance, 
                    tbl_saf_demand.fine_tax as fine_tax, 
                    tbl_saf_demand.created_on as created_on, 
                    tbl_saf_demand.status as status, 
                    tbl_saf_demand.paid_status as paid_status,
                    tbl_saf_demand.ward_mstr_id as ward_mstr_id
                    FROM tbl_saf_demand
                    INNER JOIN view_fy_mstr ON view_fy_mstr.id = tbl_saf_demand.fy_mstr_id
                    WHERE tbl_saf_demand.saf_dtl_id=".$input['saf_dtl_id']." AND tbl_saf_demand.status=1
                    ORDER BY tbl_saf_demand.paid_status DESC, tbl_saf_demand.fy_mstr_id, tbl_saf_demand.qtr ASC";
            $result = $this->db->query($sql);
            return $result->getResultArray();
            
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    
    public function getSumDemandBySafDtlIdFyIdQtr($input) {
        try{
            $builder = $this->db->table($this->table)
                                ->select('COALESCE(SUM(amount), 0) AS amount')
                                ->where('saf_dtl_id', $input['saf_dtl_id'])
                                ->where('fy_mstr_id', $input['fy_mstr_id'])
                                ->where('qtr', $input['qtr'])
                                ->where('paid_status', 1)
                                ->where('status', 1)
                                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
	
	public function total_demand_amount()
    {
		try{        
            $builder = $this->db->table("tbl_saf_demand")
                        ->select('id,fy_mstr_id,amount,balance,paid_status,status')
						->where('status', 1)
						->orderBy("id","ASC")
                        ->get();
            return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
        
    }
    public function updatePaidStatusClear($saf_demand_id){
        try{
            return $builder = $this->db->table($this->table)
                            ->where('id',$saf_demand_id)
                            ->where('status',1)
                            ->update([
                                'paid_status'=>1
                              ]);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
	public function updatePaidStatusNotClear($saf_demand_id,$amount){
        try{
            return $builder = $this->db->table($this->table)
                            ->where('id',$saf_demand_id)
                            ->where('status',1)
                            ->update([
                                'paid_status'=>0,
                                'balance'=>$amount
                              ]);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
	
	public function full_penalty($data)
    {  
		$sqlfullpnlty = "SELECT (sum(amount) - (SELECT sum(amount) FROM tbl_saf_demand where saf_dtl_id=? and fy_mstr_id>=? and qtr>?)) as full_penalty 
			FROM tbl_saf_demand where saf_dtl_id=? and fy_mstr_id>=? and fy_mstr_id<=?
			";
			$ql= $this->query($sqlfullpnlty, [$data['basic_details']['saf_dtl_id'],$data['fy_id']['id'],$data['qtr'],$data['basic_details']['saf_dtl_id'],'16',$data['fy_id']['id']]);
			//echo $this->db->getLastQuery();
			$full_penalty =$ql->getResultArray()[0];
			return $full_penalty;
    }
	
	public function full_penaltySaf($data)
    {  
		$sqlfullpnlty = "SELECT (sum(amount) - (SELECT sum(amount) FROM tbl_saf_demand where saf_dtl_id=? and fy_mstr_id>=? and qtr>?)) as full_penalty 
			FROM tbl_saf_demand where saf_dtl_id=? and fy_mstr_id>=? and fy_mstr_id<=?
			";
			$ql= $this->query($sqlfullpnlty, [$data['custm_id'],$data['fy_id']['id'],$data['qtr'],$data['custm_id'],'16',$data['fy_id']['id']]);
			//echo $this->db->getLastQuery();
			$full_penalty =$ql->getResultArray()[0];
			return $full_penalty;
    }
	
	
	
	public function gateQuarter($data)
    {
		$sql = "SELECT qtr FROM view_saf_demand
				where saf_dtl_id=? and fy_id=? and paid_status=?
				ORDER BY qtr ASC";
				$ql= $this->query($sql, [$data['prop_no'], $data['fyUpto'],'0']);
				$result = $ql->getResultArray();
				return $result;
		
	}
	
    public function getDistinctQtr($input)
    {
        $sql="select distinct qtr from tbl_saf_demand where saf_dtl_id=? and fy_mstr_id=? and paid_status=? order by qtr desc";
        $sql= $this->query($sql, [$input['saf_dtl_id'], $input['fy_mstr_id'], 0]);
        //echo $this->getLastQuery();
		return $sql->getResultArray();
    }

	public function gateQuarterlast($data)
    {
		
		$sql = "SELECT qtr FROM view_saf_demand
				where saf_dtl_id=? and fy_id=? and paid_status=?
				ORDER BY qtr DESC";
				$ql= $this->query($sql, [$data['prop_no'],$data['fyUpto'],'0']);
				$result = $ql->getResultArray()[0];
				return $result;
	}
	
	/*public function gatelasttotalQuarter($data)
    {
		try{        
            $builder = $this->db->table("view_saf_demand")
                        ->select('count(qtr)')
                        ->where('saf_dtl_id', $data['prop_no'])
						->where('fy_id', $data['fyUpto'])
						->where('paid_status', 0)
						
                        ->get();
			//return $this->db->getLastQuery();	
            return $builder->getResultArray()[0];

        }catch(Exception $e){
            return $e->getMessage();   
        }
	}*/
	
	public function fydemand($data)
    {
		$sql = "select fy,fy_id 
				FROM view_saf_demand 
				WHERE saf_dtl_id=? and paid_status=0
				Group By fy,fy_id
				Order By fy_id DESC";
				$ql= $this->query($sql, [$data]);
				$result = $ql->getResultArray();
                //echo $this->db->getLastQuery();
				return $result;
	}
	
	public function gatetotalQuarter($data)
    {
		$sql = "select count(qtr) as totalQtr
				FROM view_saf_demand 
				WHERE saf_dtl_id=? AND fy_id<=? and paid_status=0
				";
				$ql= $this->query($sql, [$data['prop_no'],$data['fyUpto']]);
				$result = $ql->getResultArray()[0];
				return $result;
	}
	
	public function resultId($data)
    {
		$sql1 = "SELECT id
			FROM tbl_saf_demand
			where saf_dtl_id=?";
			$ql= $this->query($sql1, [$data]);
			$demand_amnt =$ql->getResultArray();
			return $demand_amnt;
	}
	
	public function demand_amnt($data)
    {
		$sql1 = "SELECT balance,fy_mstr_id
			FROM tbl_saf_demand
			where saf_dtl_id=?
			order By fy_mstr_id ASC";
			$ql= $this->query($sql1, [$data]);
			$demand_amnt =$ql->getResultArray();
			return $demand_amnt;
	}
	
	public function demand_id($input)
    {
		$demand_id = "SELECT id FROM tbl_saf_demand
			WHERE fy_mstr_id>=? AND fy_mstr_id<=? AND saf_dtl_id=? AND paid_status=0
			ORDER BY id ASC";
			$ql= $this->query($demand_id, [$input['from_fy_year'],$input['due_upto_year'],$input['custm_id']]);
			$resultid =$ql->getResultArray();
			return $resultid;
	}
	
	public function updatedemandPayment($data)
    {
		$sql = "UPDATE tbl_saf_demand 
			SET balance=0,paid_status =1
			WHERE id =?";
			$ql= $this->query($sql, [$data['resultid']['id']]);
			$result2 =$ql->getResultArray();
	}
	
	public function updatedemandPaymentblnc($data)
    {
		$sql = "UPDATE tbl_saf_demand 
			SET balance=".$data['balance'].",fine_tax =". $data['tol_pent'].",paid_status =1
			WHERE id =?";
			$ql= $this->query($sql, [$data['resultid']['id']]);
			$result2 =$ql->getResultArray();
	}
	public function getAmountNotClear($saf_demand_id){
        try{
            $builder = $this->db->table($this->table)
                    ->select('amount')
                    ->where('id',$saf_demand_id)
                    ->where('status',1)
                    ->get();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
	
	public function demand_amount(){
		$demand_amnt = "SELECT sum(amount) as demand_amount FROM tbl_saf_demand
		WHERE status=1 AND paid_status=1
		";
		$ql= $this->query($demand_amnt);
		$resultamnt =$ql->getResultArray()[0];
		return $resultamnt;
    } 


	public function current_saf_demand_amount($data){
		
		$demand_amnt = "SELECT sum(amount) as demand_amount FROM tbl_saf_demand
		WHERE status=1 AND fy_mstr_id=? AND paid_status=1
		";
		$ql= $this->query($demand_amnt, [$data['fyid']]);
		$resultamnt =$ql->getResultArray()[0];
		//echo $this->db->getLastQuery();
		return $resultamnt;
    }   

	public function current_saf_houseHold($data){
		
		$demand_amnt = "SELECT count(saf_dtl_id) as houseHold FROM tbl_saf_demand
		WHERE status=1 AND fy_mstr_id=? AND paid_status=1
		group by saf_dtl_id
		";
		$ql= $this->query($demand_amnt, [$data['fyid']]);
		$resultamnt =$ql->getResultArray();
		//echo $this->db->getLastQuery();
		return $resultamnt;
    }  
	
	
	
	public function arrear_saf_demand_amount($data){
		
		$demand_amnt = "SELECT sum(amount) as arreardemand_amount FROM tbl_saf_demand
		WHERE status=1 AND fy_mstr_id<? AND paid_status=1
		";
		$ql= $this->query($demand_amnt, [$data['fyid']]);
		$resultamnt =$ql->getResultArray()[0];
		//echo $this->db->getLastQuery();
		return $resultamnt;
    }   

	public function arrear_saf_houseHold($data){
		
		$demand_amnt = "SELECT count(saf_dtl_id) as arrearhouseHold FROM tbl_saf_demand
		WHERE status=1 AND fy_mstr_id<? AND paid_status=1
		group by saf_dtl_id
		";
		$ql= $this->query($demand_amnt, [$data['fyid']]);
		$resultamnt =$ql->getResultArray();
		//echo $this->db->getLastQuery();
		return $resultamnt;
    }  
    public function getDemandDetails($ward_mstr_id)
    {
       try{        
            $builder = $this->db->table($this->table)
                    ->select('SUM(amount) as total_demand')
                    ->where('ward_mstr_id',$ward_mstr_id)
                    ->where('paid_status',0)
                    ->where('status',1)
                    ->get();
                  //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder['total_demand'];
        }catch(Exception $e){
            echo $e->getMessage();   
        }
    }
    public function updatePaidStatus($saf_demand_id){
        try{
            return $builder = $this->db->table($this->table)
                            ->where('id',$saf_demand_id)
                            ->set('paid_status',0)
                            ->set('balance','amount',false)
                            ->update();
                            // ->update([
                            //             "paid_status"=>0
                            //         ]);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
	
	
	
	public function demand_rebet($data)
    {
		try{        
            $builder = $this->db->table($this->table)
                        ->select('sum(balance),fy_mstr_id')
                        ->where('saf_dtl_id', $data)
						->where('paid_status', 0)
						->groupBy("fy_mstr_id")
						->orderBy("fy_mstr_id","DESC")
                        ->get();
						//echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
	}
	
	
	public function safdemand_deactive($data) {
         return $this->db->table($this->table)
                ->where('saf_dtl_id', $data)
                ->update([
                    'status'=>0
                ]); 
    }
    public function updateDemandNotPaid($demand_id)
    {
        $sql="update tbl_saf_demand set paid_status=0 where id in($demand_id)";
        $run=$this->db->query($sql);
        //echo $this->getLastQuery();
    }

    public function updateDemandAmount($demand_id)
    {
        $sql="update tbl_saf_demand set balance=coll.amount from tbl_saf_collection coll where coll.saf_demand_id=tbl_saf_demand.id and tbl_saf_demand.id in($demand_id)";
        $run=$this->db->query($sql);
        //echo $this->getLastQuery();
        

    }
	
	public function citizen_caldemand_amount($data){

		$sql = "SELECT *
			FROM tbl_saf_demand
			WHERE md5(prop_dtl_id::text)='".$data."' AND paid_status=0 ";
			$ql1= $this->query($sql);
			//echo $this->db->getLastQuery();
			$result1 = $ql1->getResultArray();
			return $result1;
    }
	
    public function geDuesYear($saf_dtl_id)
    {
        $sql="SELECT DISTINCT first_value(qtr) OVER wmin AS min_quarter, 
                    first_value(fyear) OVER wmin AS min_year, 
                    first_value(fy_mstr_id) OVER wmin AS min_fy_id, 
                    first_value(qtr) OVER wmax AS max_quarter, 
                    first_value(fyear) OVER wmax AS max_year,
                    first_value(fy_mstr_id) OVER wmax AS max_fy_id
            FROM tbl_saf_demand where saf_dtl_id=$saf_dtl_id and paid_status=0 

            WINDOW wmin AS (PARTITION BY saf_dtl_id ORDER BY fy_mstr_id ASC, qtr ASC), 
            wmax AS (PARTITION BY saf_dtl_id ORDER BY fy_mstr_id DESC, qtr DESC);";
        $builder = $this->db->query($sql);
		return $builder->getFirstRow('array');
    }

	function getSAFDemandAmountDetails($input)
    {
        $sql="select * from saf_getdemand($input[saf_dtl_id], '$input[fy]', $input[qtr]);";
        $sql= $this->query($sql);
        $DemandAmount=$sql->getFirstRow('array')['saf_getdemand'];

        
        $sql="select * from saf_getrebateamount($input[saf_dtl_id], '$input[fy]', $input[qtr], $input[user_id]);";
        $sql= $this->query($sql);
        $RebateAmount=$sql->getFirstRow('array')['saf_getrebateamount'];

        $sql="select * from saf_getspecialrebateamount($input[saf_dtl_id], '$input[fy]', $input[qtr]);";
        $sql= $this->query($sql);
        $SpecialRebateAmount=$sql->getFirstRow('array')['saf_getspecialrebateamount'];
        

        $sql="select * from saf_get20005000penalty($input[saf_dtl_id]);";
        $sql= $this->query($sql);
        $LateAssessmentPenalty=$sql->getFirstRow('array')['saf_get20005000penalty'];

        $sql="select * from saf_get1percentpenalty($input[saf_dtl_id], '$input[fy]', $input[qtr]);";
        $sql= $this->query($sql);
        $OnePercentPnalty=$sql->getFirstRow('array')['saf_get1percentpenalty'];

        $sql="select * from saf_getotherpenalty($input[saf_dtl_id]);";
        $sql= $this->query($sql);
        $OtherPenalty=$sql->getFirstRow('array')['saf_getotherpenalty'];

        $sql="select * from saf_getadvanceamount($input[saf_dtl_id]);";
        $sql= $this->query($sql);
        $AdvanceAmount=$sql->getFirstRow('array')['saf_getadvanceamount'];

        return [
            "DemandAmount"=> $DemandAmount,
            "RebateAmount"=> $RebateAmount,
            "SpecialRebateAmount"=> $SpecialRebateAmount,
            "LateAssessmentPenalty"=> $LateAssessmentPenalty,
            "OnePercentPnalty"=> $OnePercentPnalty,
            "OtherPenalty"=> $OtherPenalty,
            "AdvanceAmount"=> $AdvanceAmount,
            "PayableAmount"=> ($DemandAmount+$LateAssessmentPenalty+$OnePercentPnalty+$OtherPenalty)-($AdvanceAmount+$RebateAmount+$SpecialRebateAmount),
        ];
    }

	
}