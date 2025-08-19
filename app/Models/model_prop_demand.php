<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_prop_demand extends Model
{
    protected $table = 'tbl_prop_demand';
    protected $allowedFields = [''];

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	
	}
	
    public function insertData($input) {
        try{
            $data = $this->db->table($this->table)
                            ->insert($input);
            //echo "<br />Demand Query<br />";
            //echo $this->db->getLastQuery();
            return $data;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

	public function demand_detail($data)
    {
		try
        {
            $builder = $this->db->table("view_prop_demand")
                        ->select('*')
                        ->where('md5(prop_dtl_id::text)', $data['id'])
						->where('paid_status', 0)
						->orderBy("fy_id,qtr","ASC")
                        ->get();
			//echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    public function all_demand_detail($data)
    {
		try{        
            $builder = $this->db->table("view_prop_demand")
                        ->select('*')
                        ->where('md5(prop_dtl_id::text)', $data['id'])
						->orderBy("fy_id,qtr","ASC")
                        ->get();
            return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }

    }


    public function insrtpropdemand($propdtl,$prop_tax_id,$fy_mstr_id,$qtr,$amount,$balance,$fine_tax,$created_on,$ward_mstr_id){
        $builder = $this->db->table($this->table)
                            ->insert([
                  "prop_dtl_id"=>$propdtl,
                  "prop_tax_id"=>$prop_tax_id,
                  "fy_mstr_id"=>$fy_mstr_id,
                  "qtr"=>$qtr,
                   "amount"=>$amount,
                   "balance"=>$balance,
                  "fine_tax"=>$fine_tax,
                  "paid_status"=>'0',
                  "created_on"=>$created_on,
                  "ward_mstr_id"=>$ward_mstr_id,
                  "status"=>'1'
				  ]);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
    public function updatepropdemandBypropdetId($prop_dtl_id){
        return $builder = $this->db->table($this->table)
                            ->where('prop_dtl_id',$prop_dtl_id)
                            ->where('paid_status',0)
                            ->update([
                                    'saf_deactive_status'=>1
                                    ]);
    }
    public function deletepreviouspropdemandBypropdetId($prop_dtl_id){
        return $builder = $this->db->table($this->table)
                            ->where('prop_dtl_id',$prop_dtl_id)
                            ->where('paid_status', 0)
                            ->delete();
    }


	public function total_demand_amount()
    {
		try{        
            $builder = $this->db->table("tbl_prop_demand")
                        ->select('id,fy_mstr_id,amount,balance,paid_status,status')
						->where('status', 1)
						->orderBy("id","ASC")
                        ->get();
            return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }

    }

    public function getIsDemandClearedByPropDtlId($input){
		try{        
            $builder = $this->db->table($this->table)
                        ->select('id')
                        ->where('prop_dtl_id', $input['prop_dtl_id'])
                        ->where('paid_status', 0) // 0=payment is not cleared
                        ->where("fyear!='2023-2024'")
                        ->where('status', 1)
                        ->get();
            //echo $this->db->getLastQuery();exit;
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }

    }
    public function getDemandDtlByFyIdQtr($input){
        try{
            return $this->db->table($this->table)
                            ->select('id, amount')
                            ->where('prop_dtl_id', $input['prop_dtl_id'])
                            ->where('fy_mstr_id', $input['fy_mstr_id'])
                            ->where('qtr', $input['qtr'])
                            ->where('paid_status', 0)
                            ->where('status', 1)                            
                            ->get()
                            ->getFirstRow("array");
        }catch(Exception $e){
            return false;  
        }  
    }
    public function updatePaidStatusClear($prop_demand_id){
        try{
            return $builder = $this->db->table($this->table)
                     ->where('id',$prop_demand_id)
                     ->where('status',1)
                     ->update([
                                'paid_status'=>1
                              ]);
                     //echo $this->getLastQuery();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function updatePaidStatusNotClear($prop_demand_id){
        try{
            return $builder = $this->db->table($this->table)
                     ->where('id',$prop_demand_id)
                     ->where('status',1)
                     ->update([
                                'paid_status'=>0
                              ]);
                     //echo $this->getLastQuery();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

	public function citizen_demand_dtl($input) {
		$sql = "UPDATE tbl_prop_demand SET paid_status=2,balance=0
				WHERE prop_dtl_id='".$input['prop_dtl_id']."'
                AND paid_status=0 AND status=1
                AND id BETWEEN 
                    (SELECT id FROM tbl_prop_demand WHERE prop_dtl_id='".$input['prop_dtl_id']."' AND fy_mstr_id='".$input['from_fy_mstr_id']."' AND qtr=".$input['from_qtr']." AND status=1 AND paid_status=0)
                AND 
                    (SELECT id FROM tbl_prop_demand WHERE prop_dtl_id='".$input['prop_dtl_id']."' AND fy_mstr_id='".$input['upto_fy_mstr_id']."' AND qtr=".$input['upto_qtr']." AND status=1 AND paid_status=0)";
		$result= $this->db->query($sql);
        //$this->db->getLastQuery();
        return $result;

    }
	public function getDemandDeactivatedTotalAmount($input) {
		$sql = "SELECT SUM(amount) as amount FROM tbl_prop_demand 
                WHERE prop_dtl_id=".$input['prop_dtl_id']." 
                AND paid_status=0 AND status=1
                AND id BETWEEN 
                    (SELECT id FROM tbl_prop_demand WHERE prop_dtl_id='".$input['prop_dtl_id']."' AND fy_mstr_id='".$input['from_fy_mstr_id']."' AND qtr=".$input['from_qtr'].")
                AND 
                    (SELECT id FROM tbl_prop_demand WHERE prop_dtl_id='".$input['prop_dtl_id']."' AND fy_mstr_id='".$input['upto_fy_mstr_id']."' AND qtr=".$input['upto_qtr'].")";
        $result= $this->db->query($sql);
        //echo $this->db->getLastQuery();
        return $result->getFirstRow('array');
    }


    public function deleteDemandByPropDtlIdFyIdQtr($input) {
        try{
             $builder = $this->db->table($this->table)
                            ->where('prop_dtl_id', $input['prop_dtl_id'])
                            ->where('fy_mstr_id', $input['fy_mstr_id'])
                            ->where('qtr', $input['qtr'])
                            ->where('paid_status', 0)
                            ->where('status', 1)
                            ->delete();
            //echo $this->db->getLastQuery();
            return $builder;
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }


    public function checkDemandPaidStatusZeroByPropDtlIdFyIdQtr($input) {
        try{
            $builder = $this->db->table($this->table)
                                ->select('id')
                                ->where('prop_dtl_id', $input['prop_dtl_id'])
                                ->where('fy_mstr_id', $input['fy_mstr_id'])
                                ->where('qtr', $input['qtr'])
                                ->where('paid_status', 0)
                                ->where('status', 1)
                                ->get();
            //echo $this->db->getLastQuery()."<br />";
            return $builder->getFirstRow("array");
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function getSumDemandByPropDtlIdFyIdQtrWithAll($input) {
        try{
            $builder = $this->db->table($this->table)
                                ->select('COALESCE(SUM(amount), 0) AS amount')
                                ->where('prop_dtl_id', $input['prop_dtl_id'])
                                ->where('fy_mstr_id', $input['fy_mstr_id'])
                                ->where('qtr', $input['qtr'])
                                ->whereIn('paid_status', [0,1])
                                ->where('status', 1)
                                ->get();
            //echo $this->db->getLastQuery();
            return $builder->getFirstRow("array");
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function getSumDemandByPropDtlIdFyIdQtr($input) {
        try{
            
            $builder = $this->db->table($this->table)
                            ->select('COALESCE(SUM(amount), 0) AS amount')
                            ->where('prop_dtl_id', $input['prop_dtl_id'])
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

    public function getPropDemandSumByDueDate($input, $prev_saf_dtl_id) {
        try{
            if ($prev_saf_dtl_id!=0) {
                $sql = "SELECT 
                            COALESCE(SUM(demand_amount+additional_amount), 0) AS amount 
                        FROM 
                        (SELECT 
							CASE WHEN COALESCE(demand_amount, 0)=0 THEN amount ELSE COALESCE(demand_amount, 0) END AS demand_amount, 
                            CASE WHEN COALESCE(demand_amount, 0)=0 THEN 0 ELSE COALESCE(additional_amount, 0) END AS additional_amount,   
							amount, due_date, paid_status, status, qtr, fyear FROM tbl_saf_demand WHERE saf_dtl_id='".$prev_saf_dtl_id."' AND status=1 AND paid_status=1
                        UNION ALL
                        SELECT 
							CASE WHEN COALESCE(demand_amount, 0)=0 THEN amount ELSE COALESCE(demand_amount, 0) END AS demand_amount, 
                            CASE WHEN COALESCE(demand_amount, 0)=0 THEN 0 ELSE COALESCE(additional_amount, 0) END AS additional_amount,  
							amount, due_date, paid_status, status, qtr, fyear FROM tbl_prop_demand WHERE prop_dtl_id='".$input['prop_dtl_id']."' AND status=1 AND paid_status=1
                        ) AS TBL 
                        WHERE due_date='".$input['due_date']."'";
                $builder=$this->db->query($sql);
                return $builder->getFirstRow("array");
            } else {
                $builder = $this->db->table($this->table)
                                    ->select('COALESCE(SUM(amount), 0) AS amount')
                                    ->where('prop_dtl_id', $input['prop_dtl_id'])
                                    ->where('due_date', $input['due_date'])
                                    ->where('paid_status', 1)
                                    ->where('status', 1)
                                    ->get();
                return $builder->getFirstRow("array");
            }
        }catch(Exception $e){
            echo $e->getMessage();
            die();
        }
    }

    public function getLastGeneratedPropDemand($pre_prop_dtl_id, $prev_saf_dtl_id) {
        try{
            if ($pre_prop_dtl_id!=0 || $prev_saf_dtl_id!=0) {
                $sql = "SELECT 
                           demand_amount, additional_amount, amount, due_date, paid_status, status, qtr, fyear 
                        FROM 
                        (SELECT COALESCE(demand_amount, 0) AS demand_amount, COALESCE(additional_amount, 0) AS additional_amount, amount, due_date, paid_status, status, qtr, fyear FROM tbl_saf_demand WHERE saf_dtl_id='".$prev_saf_dtl_id."' AND status=1 AND paid_status=1
                        UNION ALL
                        SELECT COALESCE(demand_amount, 0) AS demand_amount, COALESCE(additional_amount, 0) AS additional_amount, amount, due_date, paid_status, status, qtr, fyear FROM tbl_prop_demand WHERE prop_dtl_id='".$pre_prop_dtl_id."' AND status=1 AND paid_status=1
                        ) AS TBL 
                        ORDER BY due_date ASC 
                        LIMIT 1";
                $builder=$this->db->query($sql);
                return $builder->getFirstRow("array");
            }
            return false;
        }catch(Exception $e){
            echo $e->getMessage();
            die();
        }
    }

    public function getGovtPropDemandSumByDueDate($input, $prev_govt_saf_dtl_id) {
        try{
            if ($prev_govt_saf_dtl_id!=0) {
                $sql = "SELECT 
                            COALESCE(SUM(amount), 0) AS amount 
                        FROM 
                        (SELECT amount, due_date, paid_status, status, qtr, fyear FROM tbl_govt_saf_demand WHERE govt_saf_dtl_id='".$prev_govt_saf_dtl_id."' AND status=1 AND paid_status=1
                        UNION ALL
                        SELECT amount, due_date, paid_status, status, qtr, fyear FROM tbl_prop_demand WHERE prop_dtl_id='".$input['prop_dtl_id']."' AND status=1 AND paid_status=1
                        ) AS TBL 
                        WHERE due_date='".$input['due_date']."'";
                $builder=$this->db->query($sql);
                return $builder->getFirstRow("array");
            } else {
                $builder = $this->db->table($this->table)
                                    ->select('COALESCE(SUM(amount), 0) AS amount')
                                    ->where('prop_dtl_id', $input['prop_dtl_id'])
                                    ->where('due_date', $input['due_date'])
                                    ->where('paid_status', 1)
                                    ->where('status', 1)
                                    ->get();
                return $builder->getFirstRow("array");
            }
        }catch(Exception $e){
            echo $e->getMessage();
            die();
        }
    }

    public function checkDemandIsExistByPropDtlIdFyIdQtr($input) {
        try{
            $builder = $this->db->table($this->table)
                                ->select('id')
                                ->where('prop_dtl_id', $input['prop_dtl_id'])
                                ->where('fy_mstr_id', $input['fy_mstr_id'])
                                ->where('qtr', $input['qtr'])
                                ->whereIn('paid_status', [0, 1])
                                ->where('status', 1)
                                ->get();
            /*echo $this->db->getLastQuery()."<br />";*/
            if ($builder) {
                return $builder->getFirstRow("array");
            } else {
                return false;
            }

        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function updateamt_balanceByproptaxId($prop_tax_id,$holding_tax){
        return $builder = $this->db->table($this->table)
                            ->where('prop_tax_id',$prop_tax_id)
                            ->where('paid_status',0)
                            ->update([
                                    'amount'=>$holding_tax,
                                    'balance'=>$holding_tax,
                                    ]);
    }
    public function updateproptaxidByfyid_qtr_propid($prop_tax_id,$previous_prop_tax_id,$fy_mstr_id,$qtr,$holding_tax){
        return $builder = $this->db->table($this->table)
                            ->where('prop_tax_id',$previous_prop_tax_id)
                            ->where('fy_mstr_id>=',$fy_mstr_id)
                            ->where('qtr>=',$qtr)
                            ->where('paid_status',0)
                            ->update([
                                    'prop_tax_id'=>$prop_tax_id,
                                    'amount'=>$holding_tax,
                                    'balance'=>$holding_tax
                                    ]);
    }


	public function gateQuarter($data)
    {
		
		$sql = "SELECT distinct qtr FROM view_prop_demand
				where prop_dtl_id=? and fy_id=? and paid_status=?
				ORDER BY qtr ASC";
				$ql= $this->db->query($sql, [$data['prop_no'], $data['fyUpto'], '0']);
				$result = $ql->getResultArray();
				return $result;
	}

    public function getDistinctQtr($input)
    {
        $sql="select distinct qtr from tbl_prop_demand where prop_dtl_id=? and fy_mstr_id=? and paid_status=? order by qtr desc";
        $sql= $this->db->query($sql, [$input['prop_dtl_id'], $input['fy_mstr_id'], 0]);
		return $sql->getResultArray();
    }

    function getPropDemandAmountDetails_old($input)
    {
        $sql="select * from prop_getdemand($input[prop_dtl_id], '$input[fy]', $input[qtr]);";
        $sql= $this->db->query($sql);
        $DemandAmount=$sql->getFirstRow('array')['prop_getdemand'];

        $sql="select * from prop_getrebateamount($input[prop_dtl_id], '$input[fy]', $input[qtr], $input[user_id]);";
        $sql= $this->db->query($sql);
        $RebateAmount=$sql->getFirstRow('array')['prop_getrebateamount'];

        $sql="select * from prop_getspecialrebateamount($input[prop_dtl_id], '$input[fy]', $input[qtr]);";
        $sql= $this->db->query($sql);
        $SpecialRebateAmount=$sql->getFirstRow('array')['prop_getspecialrebateamount'];

        $sql="select * from prop_get1percentpenalty($input[prop_dtl_id], '$input[fy]', $input[qtr]);";
        $sql= $this->db->query($sql);
		$OnePercentPnalty=$sql->getFirstRow('array')['prop_get1percentpenalty'];
		/*if($input['prop_dtl_id'] == '75133' || $input['prop_dtl_id'] == '75132')
		{
			$OnePercentPnalty='0';
		}else{
			$OnePercentPnalty=$sql->getFirstRow('array')['prop_get1percentpenalty'];
		}*/
        
		

        $sql="select * from prop_getotherpenalty($input[prop_dtl_id]);";
        $sql= $this->db->query($sql);
        $OtherPenalty=$sql->getFirstRow('array')['prop_getotherpenalty'];

        $sql="select * from prop_getadvanceamount($input[prop_dtl_id]);";
        $sql= $this->db->query($sql);
        $AdvanceAmount=$sql->getFirstRow('array')['prop_getadvanceamount'];
        
        return [
            "DemandAmount"=> number_format((float)$DemandAmount, 2, '.', ''),
            "RebateAmount"=> number_format((float)$RebateAmount, 2, '.', ''),
            "SpecialRebateAmount"=> number_format((float)$SpecialRebateAmount, 2, '.', ''),
            "OnePercentPnalty"=> number_format((float)$OnePercentPnalty, 2, '.', ''),
            "OtherPenalty"=> number_format((float)$OtherPenalty, 2, '.', ''),
            "AdvanceAmount"=> number_format((float)$AdvanceAmount, 2, '.', ''),
            "PayableAmount"=> number_format((float)(($DemandAmount+$OnePercentPnalty+$OtherPenalty)-($AdvanceAmount+$RebateAmount+$SpecialRebateAmount)), 2, '.', ''),
        ];
    }

    function getPropDemandAmountDetails($input)
    {
        $sql="select * from prop_getdemand($input[prop_dtl_id], '$input[fy]', $input[qtr]);";
        $sql= $this->db->query($sql);
        $DemandAmount=$sql->getFirstRow('array')['prop_getdemand'];

        $sql="select * from prop_getrebateamount($input[prop_dtl_id], '$input[fy]', $input[qtr], $input[user_id]);";
        $sql= $this->db->query($sql);
        $RebateAmount=$sql->getFirstRow('array')['prop_getrebateamount'];

        $sql ="select * from prop_getrebateamount_online($input[prop_dtl_id], '$input[fy]', $input[qtr], $input[user_id]);";
        $sql= $this->db->query($sql);
        $onlineRebate=$sql->getFirstRow('array')['prop_getrebateamount_online'];

        $sql ="select * from prop_getrebateamount_jsk($input[prop_dtl_id], '$input[fy]', $input[qtr], $input[user_id]);";
        $sql= $this->db->query($sql);
        $jskRebate=$sql->getFirstRow('array')['prop_getrebateamount_jsk'];
        
        $sql ="select * from prop_getrebateamount_first_qtr($input[prop_dtl_id], '$input[fy]', $input[qtr]);";
        $sql= $this->db->query($sql);
        $firstQtrRebate=$sql->getFirstRow('array')['prop_getrebateamount_first_qtr'];
        

        $sql="select * from prop_getspecialrebateamount($input[prop_dtl_id], '$input[fy]', $input[qtr]);";
        $sql= $this->db->query($sql);
        $SpecialRebateAmount=$sql->getFirstRow('array')['prop_getspecialrebateamount'];

        $sql="select * from prop_get1percentpenalty($input[prop_dtl_id], '$input[fy]', $input[qtr]);";
        $sql= $this->db->query($sql);
		$OnePercentPnalty=$sql->getFirstRow('array')['prop_get1percentpenalty'];
		/*if($input['prop_dtl_id'] == '75133' || $input['prop_dtl_id'] == '75132')
		{
			$OnePercentPnalty='0';
		}else{
			$OnePercentPnalty=$sql->getFirstRow('array')['prop_get1percentpenalty'];
		}*/

        $sql="select * from prop_getotherpenalty($input[prop_dtl_id]);";
        $sql= $this->db->query($sql);
        $OtherPenalty=$sql->getFirstRow('array')['prop_getotherpenalty'];

        $sql="select * from prop_getadvanceamount($input[prop_dtl_id]);";
        $sql= $this->db->query($sql);
        $AdvanceAmount=$sql->getFirstRow('array')['prop_getadvanceamount'];
        //dd($AdvanceAmount,$OnePercentPnalty);
        $noticePenalty = 0;
        $noticePenaltyTwoPer = 0;
        $PrivDemandAmount =0;
        $PrivOnePercentPnalty =0;
        $noticePer =0;
        $dateDiff = 0;
        $noticeSql = "select *, notice_served_on::date as notice_served_on
                    from tbl_prop_notices
                    where prop_dtl_id = ".$input["prop_dtl_id"]." 
                        and notice_type = 'Demand' and status = 1
                    order by notice_date DESC,id DESC
                    ";
                    
        if($notice = $this->db->query($noticeSql)->getFirstRow("array")){
            $lastTranSql="select * 
                        from tbl_transaction
                        where prop_dtl_id = ".$input["prop_dtl_id"]." 
                            and status in(1,2) and tran_type='Property' and tran_date >='".$notice["notice_date"]."'
            ";
            $date1=date_create($notice["notice_served_on"]);
            $date2=date_create();
            $diff=date_diff($date1,$date2);
            $dateDiff1 = $diff->format("%a");
            $monthDiff1 = ceil($dateDiff1/30);
            if(!($this->db->query($lastTranSql)->getFirstRow("array")) && $monthDiff1>1 && $notice["notice_served_on"]){
                $priFyear = getFY(date("Y-m-d",strtotime('-1 year', strtotime($notice["notice_date"]))));
                $sql="select * from prop_getdemand($input[prop_dtl_id], '$priFyear', 4);";
                $sql= $this->db->query($sql);
                $PrivDemandAmount=$sql->getFirstRow('array')['prop_getdemand'];
                $sql="select * from prop_get1percentpenalty($input[prop_dtl_id], '$priFyear', 4);";
                $sql= $this->db->query($sql);
                $PrivOnePercentPnalty=$sql->getFirstRow('array')['prop_get1percentpenalty'];

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
            "DemandAmount"=> number_format((float)$DemandAmount, 2, '.', ''),
            "RebateAmount"=> number_format((float)$RebateAmount, 2, '.', ''),
            "SpecialRebateAmount"=> number_format((float)$SpecialRebateAmount, 2, '.', ''),
            "OnePercentPnalty"=> number_format((float)$OnePercentPnalty, 2, '.', ''),
            "OtherPenalty"=> number_format((float)$OtherPenalty, 2, '.', ''),
            "noticePenalty"=>number_format((float)$noticePenalty, 2, ".", ''),
            "noticePenaltyTwoPer"=>number_format((float)$noticePenaltyTwoPer, 2, ".", ''),
            "RebateBifurcation"=>[
                "SpecialRebateAmount"=> number_format((float)$SpecialRebateAmount, 2, '.', ''),
                "onlineRebate"=> number_format((float)$onlineRebate, 2, '.', ''),
                "jskRebate"=> number_format((float)$jskRebate, 2, '.', ''),
                "firstQtrRebate"=> number_format((float)$firstQtrRebate, 2, '.', ''),

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
            "AdvanceAmount"=> number_format((float)$AdvanceAmount, 2, '.', ''),
            "PayableAmount"=> number_format((float)(($DemandAmount+$OnePercentPnalty+$OtherPenalty+$noticePenalty + $noticePenaltyTwoPer )-($AdvanceAmount+$RebateAmount+$SpecialRebateAmount)), 2, '.', ''),
        ];
    }

	
	public function gateQuarterlast($data)
    {
		
		$sql = "SELECT qtr FROM view_prop_demand
				where prop_dtl_id=? and fy_id=? and paid_status=?
				ORDER BY qtr DESC";
				$ql= $this->db->query($sql, [$data['prop_no'],$data['fyUpto'],'0']);
				$result = $ql->getResultArray()[0];
				return $result;
		
	}
	
	public function fydemand($data)
    {
		$sql = "select fy,fy_id 
				FROM view_prop_demand 
				WHERE prop_dtl_id=? and paid_status=0
				Group By fy,fy_id
				Order By fy_id DESC";
				$ql= $this->db->query($sql, [$data]);
				$result = $ql->getResultArray();
				return $result;
	}
	
	public function gatetotalQuarter($data)
    {
		$sql = "select count(qtr) as totalQtr
				FROM view_prop_demand 
				WHERE prop_dtl_id=? AND fy_id<=? and paid_status=0
				";
				$ql= $this->db->query($sql, [$data['prop_no'],$data['fyUpto']]);
				$result = $ql->getResultArray()[0];
				return $result;
	}
	
	
	public function demand_amnt($data)
    {
		$sql1 = "SELECT fy_id,balance
			FROM view_prop_demand
			where paid_status=0 and prop_dtl_id=? 
			ORDER BY fy_id ASC";
			$ql= $this->db->query($sql1, [$data]);
			$demand_amnt =$ql->getResultArray();
            //echo $this->db->getLastQuery();
            //print_var($demand_amnt);
			return $demand_amnt;
			
	}
	
	public function demand_aumnt($data)
    {
		$sql1 = "SELECT balance,fy_mstr_id
			FROM tbl_prop_demand
			where paid_status=0 and id=?";
			$ql= $this->db->query($sql1, [$data]);
			$demand_aumnts =$ql->getResultArray()[0];
			return $demand_aumnts;
	}
	
	
	public function demand_id($input)
    {
		$sql = "SELECT id FROM tbl_prop_demand WHERE fy_mstr_id>=$input[from_fy_year] AND fy_mstr_id<=$input[due_upto_year] AND prop_dtl_id=$input[custm_id] AND paid_status=0 ORDER BY id ASC";
        $builder = $this->db->query($sql);
        //echo $this->db->getLastQuery();
        return $builder->getResultArray();
	}
	
	public function updatedemandPayment($data)
    { 
		$sql = "UPDATE tbl_prop_demand 
			SET balance=0,paid_status =1
			WHERE id =?";
			$ql= $this->db->query($sql, [$data['resultid']['id']]);
			$result2 =$ql->getResultArray();
			//echo $this->db->getLastQuery();
	}
	
	public function updatedemandPaymentblnc($data)
    {
		$sql = "UPDATE tbl_prop_demand 
			SET balance=".$data['balance'].",fine_tax =". $data['tol_pent'].",paid_status =1
			WHERE id =?";
			$ql= $this->db->query($sql, [$data['resultid']['id']]);
			$result2 =$ql->getResultArray();
	}
	
	public function getpaidid_by_propdtlid($prop_dtl_id){
        try{
            return $this->db->table($this->table)
                        ->select('count(id) as count_paid_demand')
                        ->where('prop_dtl_id', $prop_dtl_id)
                        ->where('paid_status', 1)
                        ->where('status', 1)
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function updatedemandBypropdtlId($input_data){
        return $builder = $this->db->table($this->table)
                            ->where('prop_dtl_id',$input_data['prop_dtl_id'])
                            ->update([
                                    'amount'=>$input_data['amount'],
                                    'balance'=>$input_data['balance']
                                    ]);
    }
    public function updatedemandByproptaxdtlId($input_data){
        return $builder = $this->db->table($this->table)
                            ->where('prop_tax_id',$input_data['prop_tax_id'])
                            ->update([
                                    'amount'=>$input_data['amount'],
                                    'balance'=>$input_data['balance']
                                    ]);
    }
	
	public function current_demand_amount($data){
		
		$demand_amnt = "select sum(amnt)as crntamnt, count(prop_dtl_id)as crnthh from 
		(select sum(amount)as amnt,prop_dtl_id from tbl_prop_demand 
		where status=1 AND fy_mstr_id=? group by prop_dtl_id) tblfr
		";
		$ql= $this->db->query($demand_amnt, [$data['fyid']]);
		$resultamnt =$ql->getResultArray()[0];
		//echo $this->db->getLastQuery();
		return $resultamnt;
    }   
	
	public function arrear_demand_amount($data){
		
		$demand_amnt = "select sum(amnt)as arrearamnt, count(prop_dtl_id)as arrearhh from 
		(select sum(amount)as amnt,prop_dtl_id from tbl_prop_demand 
		where status=1 AND fy_mstr_id<? group by prop_dtl_id) tblarrfr
		";
		$ql= $this->db->query($demand_amnt, [$data['fyid']]);
		$resultamnt =$ql->getResultArray()[0];
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
                    ->get();
                  //echo $this->db->getLastQuery();
            $builder = $builder->getFirstRow("array");
           return  $builder['total_demand'];
        }catch(Exception $e){
            echo $e->getMessage();   
        }
    }
    public function updatePaidStatus($prop_demand_id){
        try{
            return $builder = $this->db->table($this->table)
                            ->where('id',$prop_demand_id)
                            ->where('status',1)
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

    public function getFullDemandDtlByPropDtlId($input)
    {
        try{
            $builder = $this->db->table('tbl_prop_demand')
                    ->select('
                        tbl_prop_demand.id as prop_demand_id, 
                        tbl_prop_demand.prop_dtl_id as prop_dtl_id, 
                        tbl_prop_demand.prop_tax_id as prop_tax_id, 
                        tbl_prop_demand.fy_mstr_id as fy_mstr_id,
                        view_fy_mstr.fy as fy,
                        tbl_prop_demand.qtr as qtr, 
                        tbl_prop_demand.amount as amount, 
                        tbl_prop_demand.balance as balance, 
                        tbl_prop_demand.fine_tax as fine_tax, 
                        tbl_prop_demand.created_on as created_on, 
                        tbl_prop_demand.status as status, 
                        tbl_prop_demand.paid_status as paid_status,
                        tbl_prop_demand.ward_mstr_id as ward_mstr_id        
                    ')
                    ->join('view_fy_mstr', 'view_fy_mstr.id = tbl_prop_demand.fy_mstr_id')
                    ->where('tbl_prop_demand.prop_dtl_id', $input['prop_dtl_id'])
                    ->where('tbl_prop_demand.status', 1)
                    ->orderBy('tbl_prop_demand.fy_mstr_id ASC, tbl_prop_demand.qtr ASC')
                    ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	
	public function propdemand_deactive($data) {
         return $this->db->table($this->table)
                ->where('prop_dtl_id', $data)
				->where('paid_status', 0)
                ->update([
                    'status'=>0,
					'paid_status'=>8
                ]); 
    }
	
	public function propdemand_active($data) {
         return $this->db->table($this->table)
                ->where('prop_dtl_id', $data)
				->where('paid_status', 8)
                ->update([
                    'status'=>1,
					'paid_status'=>0
                ]); 
    }
	
	
	public function citizen_caldemand_amount($data){

		$sql = "SELECT *
			FROM tbl_prop_demand
			WHERE md5(prop_dtl_id::text)='".$data."' AND paid_status=0 AND status=1 ORDER BY fyear,qtr,id ASC";
			$ql1= $this->db->query($sql);
			//echo $this->db->getLastQuery();
			$result1 = $ql1->getResultArray();
			return $result1;


    }
	
	public function demand_rebet($custmId, $fyId)
    {
		try{        
            $builder = $this->db->table($this->table)
                        ->select('sum(balance),fy_mstr_id')
                        ->where('prop_dtl_id', $custmId)
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
    public function updateDemandNotPaid($demand_id)
    {
        $sql="update tbl_prop_demand set paid_status=0 where id in($demand_id)";
        $run=$this->db->query($sql);
        //echo $this->getLastQuery();
    }
	
	public function updateDemandAmount($demand_id)
    {
        $sql="update tbl_prop_demand set balance=coll.amount from tbl_collection coll where coll.prop_demand_id=tbl_prop_demand.id and tbl_prop_demand.id in($demand_id)";
        $run=$this->db->query($sql);
        
    }

    public function selectInsertBySafDtlAndSafTaxDtlId($saf_dtl_id, $saf_tax_id, $prop_dtl_id, $prop_tax_id, $created_on)
    {
        $sql = "INSERT INTO tbl_prop_demand 
        (prop_dtl_id, prop_tax_id, fy_mstr_id, qtr, amount, balance, fine_tax, paid_status, created_on, status, ward_mstr_id, fyear, due_date, demand_amount, additional_amount)
        SELECT
            ".$prop_dtl_id.", ".$prop_tax_id.", fy_mstr_id, qtr, amount, balance, fine_tax, paid_status, '".$created_on."', status, ward_mstr_id, fyear, due_date, demand_amount, additional_amount 
        FROM tbl_saf_demand WHERE saf_dtl_id=".$saf_dtl_id." AND saf_tax_id=".$saf_tax_id." AND paid_status=0 AND status=1";
        $this->db->query($sql);
    }
	
	public function selectInsertByGBSafDtlAndSafTaxDtlId($govt_saf_dtl_id, $saf_tax_id, $prop_dtl_id, $prop_tax_id, $created_on)
    {
        $sql = "INSERT INTO tbl_prop_demand 
        (prop_dtl_id, prop_tax_id, fy_mstr_id, qtr, amount, balance, fine_tax, paid_status, created_on, status, ward_mstr_id)
        SELECT
            ".$prop_dtl_id.", ".$prop_tax_id.", fy_mstr_id, qtr, amount, balance, fine_tax, paid_status, '".$created_on."', status, ward_mstr_id
        FROM tbl_govt_saf_demand_dtl WHERE govt_saf_dtl_id=".$govt_saf_dtl_id." AND govt_saf_tax_dtl_id=".$saf_tax_id." AND paid_status=0 AND status=1";
        $this->db->query($sql);
    }
	
	public function updatePropdemandpaidStatus($prop_dtl_id,$check_status,$paid_status){
        return $builder = $this->db->table($this->table)
                            ->where('prop_dtl_id',$prop_dtl_id)
                            ->where('paid_status',$check_status)
                            ->update([
                                    'paid_status'=>$paid_status
                                    ]);
    }

    public function updatePropdemandpaidStatusActDeact($prop_dtl_id,$check_status,$paid_status,$demand_status){
        return $builder = $this->db->table($this->table)
                            ->where('prop_dtl_id',$prop_dtl_id)
                            ->where('paid_status',$check_status)
                            ->update([
                                    'paid_status'=>$paid_status,
                                    'status'=>$demand_status
                                    ]);
    }
	
	
	
	public function arear_dnd_amount($data,$fromdate,$toDate){

		$sql = "WITH totaldemand
				as (SELECT sum(amount) as balanceamnt,count(DISTINCT prop_dtl_id) as propcount 
				FROM tbl_prop_demand 
				WHERE fy_mstr_id<'".$data['fyid']."'
				),

				totalcolldmnd
				as (SELECT sum(t1.amount) as amntcoll,count(DISTINCT t1.prop_dtl_id) as propcoll
				FROM tbl_collection t1 
				inner join tbl_transaction t2 on t2.id = t1.transaction_id
				WHERE t1.fy_mstr_id<'".$data['fyid']."' and t2.tran_date < '$fromdate' and t1.status!=0
				),
				totalcoll
				as (SELECT sum(t1.amount) as amntdndcoll,count(DISTINCT t1.prop_dtl_id) as propdndcoll
				FROM tbl_collection t1 
				inner join tbl_transaction t2 on t2.id = t1.transaction_id
				WHERE t1.fy_mstr_id<'".$data['fyid']."' and t2.tran_date BETWEEN '$fromdate' AND '$toDate'
				and t1.status=1
				)
				 select (balanceamnt-amntcoll)as amnt,(propcount-propcoll)as prpcnt,amntdndcoll,propdndcoll
				 from totalcoll,totaldemand,totalcolldmnd  ";
				 
			$ql1= $this->db->query($sql);
			//echo $this->db->getLastQuery();
			$result1 = $ql1->getResultArray()[0];
			return $result1;


    }
	
	
	
	public function uniqcitezndue($data)
    {
		//print_r($data);
		try{        
            $builder = $this->db->table("view_prop_demand")
                        ->select('*')
                        ->where('prop_dtl_id', $data['prop_dtl_id'])
						->where('paid_status', 0)
						->orderBy("fy_id,qtr","ASC")
                        ->get();
						//echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	
	
	public function propdcb(){
		
		$demand_amnt = "select sum(amnt)as crntamnt from 
		(select sum(amount)as amnt from tbl_prop_demand 
		where status=1 group by prop_dtl_id) tblfr
		";
		$ql= $this->db->query($demand_amnt);
		$resultamnt =$ql->getResultArray()[0];
		//echo $this->db->getLastQuery();
		return $resultamnt;
    }   
	
    public function geDuesYear($prop_dtl_id)
    {
        $sql="SELECT DISTINCT first_value(qtr) OVER wmin AS min_quarter, 
                    first_value(fyear) OVER wmin AS min_year, 
                    first_value(fy_mstr_id) OVER wmin AS min_fy_id, 
                    first_value(qtr) OVER wmax AS max_quarter, 
                    first_value(fyear) OVER wmax AS max_year,
                    first_value(fy_mstr_id) OVER wmax AS max_fy_id
            FROM tbl_prop_demand where prop_dtl_id=$prop_dtl_id and paid_status=0 and status=1

            WINDOW wmin AS (PARTITION BY prop_dtl_id ORDER BY fy_mstr_id ASC, qtr ASC), 
            wmax AS (PARTITION BY prop_dtl_id ORDER BY fy_mstr_id DESC, qtr DESC);";
        $builder = $this->db->query($sql);
		return $builder->getFirstRow('array');
    }
}
