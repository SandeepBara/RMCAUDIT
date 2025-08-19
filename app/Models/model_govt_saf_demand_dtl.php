<?php
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_govt_saf_demand_dtl extends Model
{
	protected $db;
    protected $table = 'tbl_govt_saf_demand_dtl';
    protected $allowedFields = [];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    
	


	public function demand_detail($input)
    { 
		$sql = "SELECT tb1.*, tb2.fy
		FROM tbl_govt_saf_demand_dtl tb1
		left join view_fy_mstr tb2 on tb2.id= tb1.fy_mstr_id
		where govt_saf_dtl_id=? AND paid_status=0 AND tb1.status=1 
		ORDER BY due_date ASC";
        $ql= $this->query($sql, [$input]);
		//echo $this->db->getLastQuery();
		if($ql){
			$result = $ql->getResultArray();
			return $result;
		}else{
			return false;
		}
    }
	
	
	public function fydemand($data)
    {
		$sql = "select fy_mstr_id as fy_id,	fyear as fy FROM tbl_govt_saf_demand_dtl WHERE govt_saf_dtl_id=? and paid_status=0 group by fy_mstr_id,fyear order by fyear desc";
				$ql= $this->query($sql, [$data]);
				//echo $this->db->getLastQuery();
				$result = $ql->getResultArray();
				return $result;
	}
	
	public function gateQuarter($data)
    {
		$sql = "SELECT qtr FROM tbl_govt_saf_demand_dtl
				where govt_saf_dtl_id=? and fy_mstr_id=? and paid_status=?
				ORDER BY qtr ASC";
				$ql= $this->query($sql, [$data['prop_no'],$data['fyUpto'],'0']);
				$result = $ql->getResultArray();
				return $result;
		
	}

	
	
	public function gatetotalQuarter($data)
    {
		$sql = "select count(qtr) as totalQtr
				FROM tbl_govt_saf_demand_dtl 
				WHERE govt_saf_dtl_id=? AND fy_mstr_id<=? and paid_status=0
				";
				$ql= $this->query($sql, [$data['prop_no'],$data['fyUpto']]);
				$result = $ql->getResultArray()[0];
				return $result;
	}
	
	
	public function demand_amnt($data)
    {
		$sql1 = "SELECT balance,fy_mstr_id
			FROM tbl_govt_saf_demand_dtl
			where govt_saf_dtl_id=?
			order By fy_mstr_id ASC";
			$ql= $this->query($sql1, [$data]);
			$demand_amnt =$ql->getResultArray();
			return $demand_amnt;
	}
	
	public function demand_rebet($custmId,$fyId)
    {
		try{        
            $builder = $this->db->table($this->table)
                        ->select('sum(balance),fy_mstr_id')
                        ->where('govt_saf_dtl_id', $custmId)
						->where('fy_mstr_id', $fyId)
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
	
	
	public function demand_id($input)
    {
		$demand_id = "SELECT id FROM tbl_govt_saf_demand_dtl
			WHERE fy_mstr_id>=? AND fy_mstr_id<=? AND govt_saf_dtl_id=? AND paid_status=0
			ORDER BY id ASC";
			$ql= $this->query($demand_id, [$input['from_fy_year'],$input['due_upto_year'],$input['custm_id']]);
			$resultid =$ql->getResultArray();
			return $resultid;
	}
    
	
	public function updatedemandPayment($data)
    {
		$sql = "UPDATE tbl_govt_saf_demand_dtl 
			SET balance=".$data['dmblnc']." ,paid_status =1
			WHERE id =?";
			$ql= $this->query($sql, [$data['resultid']['id']]);
			//echo $this->getLastQuery();
			$result2 =$ql->getResultArray();
	}
	public function updateDemandNotPaid($demand_id)
	{
		$sql="update tbl_govt_saf_demand_dtl set paid_status=0 where id in($demand_id)";
		$run=$this->db->query($sql);
		//echo $this->getLastQuery();
	}






	// by hayat
	function getGovtSAFDemandAmountDetails($input)
    {
        $sql="select * from govt_saf_getdemand($input[govt_saf_dtl_id], '$input[fy]', $input[qtr]);";
        $sql= $this->query($sql);
        $DemandAmount=$sql->getFirstRow('array')['govt_saf_getdemand'];

        
        $sql="select * from govt_saf_getrebateamount($input[govt_saf_dtl_id], '$input[fy]', $input[qtr]);";
        $sql= $this->query($sql);
        $RebateAmount=$sql->getFirstRow('array')['govt_saf_getrebateamount'];


        $sql="select * from govt_saf_get20005000penalty($input[govt_saf_dtl_id]);";
        $sql= $this->query($sql);
        $LateAssessmentPenalty=$sql->getFirstRow('array')['govt_saf_get20005000penalty'];

        $sql="select * from govt_saf_get1percentpenalty($input[govt_saf_dtl_id], '$input[fy]', $input[qtr]);";
        $sql= $this->query($sql);
        $OnePercentPnalty=$sql->getFirstRow('array')['govt_saf_get1percentpenalty'];
        //$OnePercentPnalty = 0;

        $sql="select * from govt_saf_getotherpenalty($input[govt_saf_dtl_id]);";
        $sql= $this->query($sql);
        $OtherPenalty=$sql->getFirstRow('array')['govt_saf_getotherpenalty'];

        $sql="select * from govt_saf_getadvanceamount($input[govt_saf_dtl_id]);";
        $sql= $this->query($sql);
        $AdvanceAmount=$sql->getFirstRow('array')['govt_saf_getadvanceamount'];

		$noticePenalty = 0;
		$noticePenaltyTwoPer = 0;
		$PrivDemandAmount =0;
		$PrivOnePercentPnalty =0;
		$noticePer =0;
		$dateDiff = 0;

		$noticeSql = "select *, notice_served_on::date as notice_served_on
				from tbl_govt_saf_notices
				where govt_saf_dtl_id = ".$input["govt_saf_dtl_id"]." 
					and notice_type = 'Demand' and status = 1
				order by notice_date DESC,id DESC
		";

		if($notice = $this->db->query($noticeSql)->getFirstRow("array")){
			$lastTranSql="select * 
						from tbl_govt_saf_transaction
						where govt_saf_dtl_id = ".$input["govt_saf_dtl_id"]." 
							and status in(1,2) and tran_date >='".$notice["notice_date"]."'
			";
			$date1=date_create($notice["notice_served_on"]);
			$date2=date_create();
			$diff=date_diff($date1,$date2);
			$dateDiff1 = $diff->format("%a");
			$monthDiff1 = ceil($dateDiff1/30);
			if(!($this->db->query($lastTranSql)->getFirstRow("array")) && $monthDiff1>1 && $notice["notice_served_on"]){
				$priFyear = getFY(date("Y-m-d",strtotime('-1 year', strtotime($notice["notice_date"]))));
				$sql="select * from govt_saf_getdemand($input[govt_saf_dtl_id], '$priFyear', 4);";
				$sql= $this->db->query($sql);
				$PrivDemandAmount=$sql->getFirstRow('array')['govt_saf_getdemand'];
				$sql="select * from govt_saf_get1percentpenalty($input[govt_saf_dtl_id], '$priFyear', 4);";
				$sql= $this->db->query($sql);
				$PrivOnePercentPnalty=$sql->getFirstRow('array')['govt_saf_get1percentpenalty'];
	
				$date1=date_create($notice["notice_served_on"]);
				$date2=date_create();
				$diff=date_diff($date1,$date2);
				$dateDiff = $diff->format("%a") - 30 ;
				$weakDiff = ceil($dateDiff/7);
				$monthDiff = ceil($dateDiff/30);
				$demandWithPenalty =($PrivDemandAmount+$PrivOnePercentPnalty);
				
				if($weakDiff<=1){
					$noticePer = 0.01;
					$noticePenalty = $demandWithPenalty*$noticePer;
				}elseif($weakDiff <= 2){
					$noticePer = 0.02;
					$noticePenalty = $demandWithPenalty*$noticePer;
				}elseif($monthDiff <= 1){
					$noticePer = 0.03;
					$noticePenalty = $demandWithPenalty*$noticePer;
				}elseif($monthDiff <= 2){
					$noticePer = 0.05;
					$noticePenalty = $demandWithPenalty*$noticePer;
				}elseif($monthDiff > 2){      
					$noticePer = 0.05;              
					$noticePenalty = $demandWithPenalty * $noticePer;
					$noticePenaltyTwoPer = ($demandWithPenalty + $noticePenalty) * (($monthDiff-2) * 0.02) ;
				}
			}
		}
		
        return [
            "DemandAmount"=> $DemandAmount,
            "RebateAmount"=> $RebateAmount,
            "LateAssessmentPenalty"=> $LateAssessmentPenalty,
            "OnePercentPnalty"=> $OnePercentPnalty,
            "OtherPenalty"=> $OtherPenalty,
			
			"noticePenalty"=>number_format((float)$noticePenalty, 2, ".", ''),
            "noticePenaltyTwoPer"=>number_format((float)$noticePenaltyTwoPer, 2, ".", ''),
            "RebateBifurcation"=>[
                "SpecialRebateAmount"=> number_format((float)($SpecialRebateAmount??0), 2, '.', ''),
                "onlineRebate"=> number_format((float)($onlineRebate??0), 2, '.', ''),
                "jskRebate"=> number_format((float)($jskRebate??0), 2, '.', ''),
                "firstQtrRebate"=> number_format((float)($RebateAmount??0), 2, '.', ''),

            ],
            "noticePenaltyBifurcation" => [
                "NoticeDate"=> $notice["notice_date"]??"",
                "NoticeServedDate"=> $notice["notice_served_on"] ??"",
                "ArrearDemand"=> number_format((float)$PrivDemandAmount, 2, ".", ''),
                "OnePercentPnalty"=> number_format((float)$PrivOnePercentPnalty, 2, ".", ''),
                "DayDiff"=>$dateDiff1,
                "noticePer"=>number_format((float)$noticePer, 2, ".", ''),
                "noticePenalty"=>number_format((float)$noticePenalty, 2, ".", ''),
                "noticePenaltyTwoPer"=>number_format((float)$noticePenaltyTwoPer, 2, ".", ''),
            ],

            "AdvanceAmount"=> $AdvanceAmount,
            "PayableAmount"=> round(($DemandAmount+$LateAssessmentPenalty+$OnePercentPnalty+$OtherPenalty  + $noticePenalty + $noticePenaltyTwoPer )-($AdvanceAmount+$RebateAmount)),
        ];
    }


	public function govt_saf_pay_now($input, $cheque_dtl=[])
    {
        // $sql="select * from govt_saf_pay_now($input[govt_saf_dtl_id], '$input[fy]', $input[qtr], $input[user_id], '$input[payment_mode]', '$input[remarks]', $input[total_payable_amount]);";
		$sql="select * from govt_saf_pay_now_new($input[govt_saf_dtl_id], '$input[fy]', $input[qtr], $input[user_id], '$input[payment_mode]', '$input[remarks]', $input[total_payable_amount]);";
        $builder=$this->db->query($sql);
        // $trxn_id= $builder->getFirstRow('array')['govt_saf_pay_now'];
		$trxn_id= $builder->getFirstRow('array')['govt_saf_pay_now_new'];

        if($trxn_id && !empty($cheque_dtl))
        {
            $this->db->table("tbl_govt_saf_transaction_details")
                        ->Insert([
                            "govt_saf_dtl_id"=> $input["govt_saf_dtl_id"],
                            "govt_saf_transaction_id"=> $trxn_id,
                            "cheque_date"=> $cheque_dtl["cheque_date"],
							"cheque_no"=> $cheque_dtl["cheque_no"],
                            "bank_name"=> $cheque_dtl["bank_name"],
                            "branch_name"=> $cheque_dtl["branch_name"],
                            "bounce_status"=> 0, // Not Bounced
                            "status"=> 2, // Not Cleared
                            "created_on"=> "NOW()",
                        ]);
            $this->db->insertID();
        }
        return $trxn_id;
    }


	public function getDistinctQtr($input)
    {
        $sql="select distinct qtr from tbl_govt_saf_demand_dtl where govt_saf_dtl_id=? and fy_mstr_id=? and paid_status=? order by qtr desc";
        $sql= $this->query($sql, [$input['govt_saf_dtl_id'], $input['fy_mstr_id'], 0]);
        //echo $this->getLastQuery();
		return $sql->getResultArray();
    }
}
?>
